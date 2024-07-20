<?php

namespace App\Http\Controllers;

use App\Models\signature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log as FacadesLog;

class SignatureController extends Controller
{
    public function index()
    {
        return view(
            'WebsitePages.SuperAdmin.choose',
            [
                "title" => 'Signature',
                "compony_details" => ["name" => "Signature", "developed" => "ServiceHub"],
            ]
        );
    }
    public function postSignature(Request $request)
    {
        $rules['username'] = ["required"];
        $rules['HcpDesignation'] = ["required"];
        $rules['contact'] = ["required"];
        $rules['HospitalName'] = ["required"];
        $rules['city'] = ["required"];
        $rules['esign'] = ["required"];
        $rules['specialty'] = ["required"];
        $rules['report'] = ["required"];

        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }
        try {
            $file = $request->file('report');
            $fileName12 = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $publicPath = public_path('reports');
            if (!file_exists($publicPath)) {
                mkdir($publicPath, 0755, true);
            }
            $file->move($publicPath, $fileName12);

            DB::beginTransaction();
            $values = array(
                "Your signature conveys confidence professionalism, and authenticity with each swirl and curve. It speaks volumes about you. It demonstrates your confidence, originality, and attention to detail. Continue signing your story with pride.",
                "Your signature reflects your particular personality: bold, distinct, and full of possibilities. It is a declaration of your honesty, dependability and dedication to greatness. With each stroke, you create a lasting impression of trust.",
                "Your signature tells a story of determination, endurance, and self-assurance. It demonstrates your strength, both on paper and in life. Continue to write your narrative ,with boldness and confidence.",
                "Your signature is a work of art, demonstrating your taste for beauty and sophistication. It is more than simply a name; it's a blend of confidence, elegance, and refinement. It reflects your poise, grace, and the distinct essence of who you are.",
                "Your signature exudes vitality, optimism and zest for life. With each sign, you reaffirm your place in the world, with dignity and integrity. Let it serve as a reminder to approach, each day with enthusiasm and a grin.",
                "Your signature unfolds a story of professionalism, authenticity and a commitment to achievement. It is not merely ink on paper; it is an expression, of your personality. Keep exuding confidence wherever you sign!"
            );

            // $values = array("professional", "excellent", "perfect", "average");

            // Get a random key from the array
            $randomKey = array_rand($values);

            $data = signature::create([
                'username' => $request['username'],
                'HcpDesignation' => $request['HcpDesignation'],
                'contact' => $request['contact'],
                'HospitalName' => $request['HospitalName'],
                'specialty' => $request['specialty'],
                'city' => $request['city'],
                'esign' => $request['esign'],
                'OtherHcpDesignation' => $request['OtherHcpDesignation'],
                'other' => $request['other'],
                'report' => $fileName12,
                'ai' => $values[$randomKey],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            DB::commit();
            $request->session()->flash('username', $request->input('username'));
            return self::schedule_id_edit_frame($data->id);
        } catch (\Exception $e) {
            DB::rollBack();
            FacadesLog::error('Error updating postchooseid: ' . $e->getMessage());
            return back()->withErrors(['issue' => 'Update failed postchooseid'])->withInput();
        }
    }
    public static function schedule_id_edit_frame($id)
    {
        try {
            $doctor_data = signature::where('id', $id)->first();
            $fileName = uniqid() . '_' . time() . '.png';

            // Path to the frame image
            $framePath = public_path("reports//mx.png");
            $frame = imagecreatefrompng($framePath);

            // Path to the photo image (ensure it's in PNG format)
            $photoPath1 = $doctor_data->report;
            $imageData1 = file_get_contents(public_path('reports/' . $photoPath1));
            $photo = imagecreatefromstring($imageData1);
            // Get the dimensions of the frame image
            $frameWidth = imagesx($frame);
            $frameHeight = imagesy($frame);

            // Resize the photo image to fit into the frame
            $photoWidth = $frameWidth * 0.3;
            $photoHeight = $frameHeight * 0.22;
            $resizedPhoto = imagescale($photo, $photoWidth, $photoHeight);

            // Create a blank image to serve as the mask
            $mask = imagecreatetruecolor($photoWidth, $photoHeight);
            $maskTransparent = imagecolorallocate($mask, 0, 0, 0);
            imagecolortransparent($mask, $maskTransparent);

            // Calculate the radius of the circular mask
            $radius = min($photoWidth, $photoHeight) / 2;

            // Apply the circular mask
            imagefilledellipse($mask, $photoWidth / 2, $photoHeight / 2, $radius * 2, $radius * 2, $maskTransparent);

            // Apply the circular mask to the resized photo
            imagecopymerge($resizedPhoto, $mask, 0, 0, 0, 0, $photoWidth, $photoHeight, 100);

            // Merge the masked photo onto the frame
            imagecopymerge($frame, $resizedPhoto, 448, 110, 0, 0, $photoWidth, $photoHeight, 100);
            // Handle company details if empty
            // Save the merged image
            // Add text onto the image
            //  $textColor = imagecolorallocate($frame, 255, 255, 255); // White color
            // $textColor = imagecolorallocate($frame, 0, 0, 0); // Black color
            // $text = $doctor_data->ai;
            // $font = public_path('font/Arial.ttf'); // Path to your font file
            // imagettftext($frame, 20, 0, 227, 1096, $textColor, $font, $text);
            $textColor1 = imagecolorallocate($frame, 0, 100, 0); // Black color
            $text1 = strtoupper($doctor_data->username);
            $font1 = public_path('font/Arial.ttf'); // Path to your font file
            imagettftext($frame, 40, 0, 465, 604, $textColor1, $font1, $text1);


            $textColor = imagecolorallocate($frame, 0, 100, 0); // Black color
            $text = $doctor_data->ai;
            $font = public_path('font/Arial.ttf'); // Path to your font file

            // Explode the text by comma and period characters
            $text_segments = preg_split('/[,\.]/', $text);

            // Remove the last segment if it's empty (due to the last period)
            if (end($text_segments) === '') {
                array_pop($text_segments);
            }

            // Initial y-coordinate for the first line
            $y_coordinate = 1467;

            // Loop through each line and render it with imagettftext
            foreach ($text_segments as $segment) {
                // Trim whitespace from each segment
                $segment = trim($segment);

                imagettftext($frame, 20, 0, 348, $y_coordinate, $textColor, $font, $segment);

                // Increment the y-coordinate for the next line
                $y_coordinate += 30; // Adjust this value as needed for spacing between lines
            }


            imagepng($frame, public_path('Certificate/' . $fileName));

            // Update database with file name
            signature::where('id', $id)->update([
                'upload_report' => $fileName,
            ]);
            // Output or save the resulting image
            // header('Content-Type: image/png');
            // imagepng($mainImg, public_path('result_image.png'));

            // Free up memory
            $doctor_data = signature::where('id', $id)->first();
            return view(
                'WebsitePages.SuperAdmin.schedule_id_edit_frame',
                [
                    "title" => 'Signature',
                    "compony_details" => ["name" => "Signature", "developed" => "ServiceHub"],
                    'doctor_data' => $doctor_data,
                ]
            );
        } catch (\Exception $e) {
            FacadesLog::error('Error updating add_person_page: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public static function schedule_id_edit_frame1($id)
    {
        try {
            ini_set('memory_limit', '700M');
            $doctor_data = signature::where('id', $id)->first();
            $fileName = uniqid() . '_' . time() . '.png';

            // Path to the frame image
            $framePath = public_path("reports/mx.png");
            $frame = imagecreatefrompng($framePath);

            // Path to the esign image (ensure it's in PNG format)
            $imageData15 = $doctor_data->esign;
            $imageData = explode(',', $imageData15);
            $imageData = base64_decode($imageData[1]);
            $usernameFolder = public_path('esign_images/');
            $fileName10 = uniqid() . '_' . time() . '.png';
            file_put_contents($usernameFolder . '/' . $fileName10, $imageData);

            // Load the esign image
            $imageData1 = file_get_contents(public_path('esign_images/' . $fileName10));
            $photo = imagecreatefromstring($imageData1);

            // Get the dimensions of the frame image
            $frameWidth = imagesx($frame);
            $frameHeight = imagesy($frame);

            // Resize the photo image to fit into the frame
            $photoWidth = $frameWidth * 0.17;
            $photoHeight = $frameHeight * 0.17;
            $resizedPhoto = imagescale($photo, $photoWidth, $photoHeight);

            // Apply transparency settings
            imagealphablending($frame, true);
            imagesavealpha($frame, true);

            // Merge the photo onto the frame with proper alpha blending
            imagecopymerge($frame, $resizedPhoto, 1000, -15, 0, 0, $photoWidth, $photoHeight, 100);

            // Add text onto the image
            $textColor = imagecolorallocatealpha($frame, 255, 255, 255, 0); // White color with alpha
            $text = $doctor_data->name;
            $font = public_path('font/Arial.ttf'); // Path to your font file
            imagettftext($frame, 20, 0, 1043, 320, $textColor, $font, $text);

            // Save the merged image
            imagepng($frame, public_path('Certificate/' . $fileName));

            // Update database with file name
            signature::where('id', $id)->update([
                'upload_report' => $fileName,
            ]);

            // Clean up resources
            imagedestroy($frame);
            imagedestroy($photo);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            FacadesLog::error('Error updating add_person_page: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
