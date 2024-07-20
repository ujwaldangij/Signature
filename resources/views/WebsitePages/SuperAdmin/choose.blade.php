@extends('layout.WebsiteLayout.SuperAdmin.login_register')
@section('title')
    {{ $title }}
@endsection
@section('links')
@endsection
<div class="container">
    <div class="row justify-content-center">
        <div class="col">
            <a href="https://mepiform.in/">
                <img src="{{ asset('logo/Mepiform_Logo.jpg') }}" alt="Logo 1" class="img-fluid">
            </a>
        </div>
        <div class="col">
            <a href="https://www.molnlycke.in/">
                <img src="{{ asset('logo/Molnycke_Logo.jpg') }}" alt="Logo 2" class="img-fluid">
            </a>
        </div>
    </div>
</div>
@section('content')

    <div>
        <img src="{{ asset('logo/wp.png') }}" alt="loading" srcset="">
        {{-- <h3>Welcome to {{ $title }} page</h3> --}}
        {{-- <p>{{ $title }} in. To see it in action.</p> --}}
        <h4><b>
                @error('issue')
                    {{ $message }}
                @enderror
            </b></h4>
        <form class="m-t" role="form" action="{{ route('postSignature') }}" enctype="multipart/form-data" method="POST" autocomplete="off">
            @csrf
            {{-- !from input field username --}}
            <div class="form-group @error('username'){{ 'has-error' }} @enderror">
                <input type="text" class="form-control" placeholder="Enter Your HCP Name" name="username" id="username"
                    value="{{ old('username') }}">
            </div>
            <p class="py-0 text-danger text-small" style="text-align-last : left !important">
                @error('username')
                    {{ $message }}
                @enderror
            </p>
            {{-- !from input field HcpDesignation --}}
            <div class="form-group @error('HcpDesignation'){{ 'has-error' }} @enderror">
                <label for="HcpDesignation">Hcp Department & Designation</label>
                <select class="form-control" name="HcpDesignation" id="HcpDesignationSelect">
                    <option value="">Select Hcp Department & Designation</option>
                    <option value="Critical Care" {{ old('HcpDesignation') == 'Critical Care' ? 'selected' : '' }}>Critical Care</option>
                    <option value="Ortho" {{ old('HcpDesignation') == 'Ortho' ? 'selected' : '' }}>Ortho</option>
                    <option value="Gyane" {{ old('HcpDesignation') == 'Gyane' ? 'selected' : '' }}>Gyane</option>
                    <option value="Others" {{ old('HcpDesignation') == 'Others' ? 'selected' : '' }}>Others</option>
                </select>
            </div>
            
            <div class="form-group others-input" style="display: none;">
                <label for="OtherHcpDesignation">Other Hcp Designation</label>
                <input type="text" class="form-control" placeholder="Enter Other Hcp Designation" name="OtherHcpDesignation"
                    id="OtherHcpDesignation" value="{{ old('OtherHcpDesignation') }}">
            </div>
            
            <p class="py-0 text-danger text-small" style="text-align-last: left !important">
                @error('HcpDesignation')
                    {{ $message }}
                @enderror
            </p>

            {{-- !! --}}
            {{-- !from input field specialty --}}
            <label for="doctor_name">Area of Interest</label>
            <select class="form-control" name="specialty" id="specialty" onchange="showContactInput()">
                <option value="Pressure Injury Prevention & Management">Pressure Injury Prevention & Management</option>
                <option value="Wound care nurse">Wound care nurse</option>
                <option value="SSI prevention">SSI prevention</option>
                <option value="Chronic wound management">Chronic wound management</option>
                <option value="Quality improvement project">Quality improvement project</option>
                <option value="Advance wound care workshop">Advance wound care workshop</option>
                <option value="Other">Other</option> <!-- Added option -->
            </select>

            {{-- !from input field other --}}
            <div class="form-group" id="otherInput" style="display: none;">
                <label for="OtherHcpDesignation">Other Interest</label>
                <input type="text" class="form-control mt-5" placeholder="Enter Other  Interest" name="other" id="other" value="{{ old('other') }}">
            </div>

            <p class="py-0 text-danger text-small" style="text-align-last: left !important">
                @error('other')
                    {{ $message }}
                @enderror
            </p>
            
            {{-- !from input field contact --}}
            <div class="form-group @error('contact'){{ 'has-error' }} @enderror">
                <input type="number" class="form-control mt-5" placeholder="Enter Your Mobile Number" name="contact"
                    id="contact" value="{{ old('contact') }}">
            </div>
            <p class="py-0 text-danger text-small" style="text-align-last : left !important">
                @error('contact')
                    {{ $message }}
                @enderror
            </p>
            {{-- !from input field HospitalName --}}
            <div class="form-group @error('HospitalName'){{ 'has-error' }} @enderror">
                <input type="text" class="form-control" placeholder="Enter Your Hospital Name" name="HospitalName"
                    id="HospitalName" value="{{ old('HospitalName') }}">
            </div>
            <p class="py-0 text-danger text-small" style="text-align-last : left !important">
                @error('HospitalName')
                    {{ $message }}
                @enderror
            </p>
            {{-- !from input field city --}}
            <div class="form-group @error('city'){{ 'has-error' }} @enderror" id="additional_field_city">
                <input type="text" class="form-control" placeholder="Enter Your Hospital City" name="city"
                    id="city" value="{{ old('city') }}" style="width: 100%; max-width: 400px;">
                <p class="py-0 text-danger text-small" style="text-align-last: left !important">
                    @error('city')
                        {{ $message }}
                    @enderror
                </p>
            </div>
            {{-- ! E-Signature Field --}}
            <div class="form-group @error('esign'){{ 'has-error' }}@enderror" id="additional_field_esign">
                <label for="esign">E-Signature</label>
                <div style="width: 100%; max-width: 400px;">
                    <canvas id="signatureCanvas" style="width: 100%; height: auto; border: 1px solid #000;"></canvas>
                </div>
                <input type="hidden" id="esign" name="esign">
                <button type="button" onclick="clearSignature()">Clear Signature</button>
                <button type="button" onclick="saveSignature()">Save Signature</button>

                @error('esign')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
            {{-- !from input field report --}}
            <div class="form-group @error('report'){{ 'has-error' }} @enderror">
            <label for="">Photo</label>

                <input type="file" class="form-control" placeholder="Enter Your report" name="report" id="report"
                    value="{{ old('report', $doctor_data->report ?? '') }}" accept=".jpg, .jpeg, .png">
            </div>
            <p class="py-0 text-danger text-small" style="text-align-last : left !important">
                @error('report')
                    {{ $message }}
                @enderror
            </p>

    </div>
    <button type="submit" class="btn btn-primary block full-width m-b">Submit</button>
    </form>
    <p class="m-t"> <small>{{ $compony_details['name'] }} is developed by {{ $compony_details['developed'] }}
            &copy;
            {{ date('Y') }}</small> </p>
    </div>
@endsection
@section('script')
    <script>
        // public/js/signature.js
    
    const canvas = document.getElementById('signatureCanvas');
    const ctx = canvas.getContext('2d');
    let isDrawing = false;
    
    // Function to get the position of touch relative to the canvas
    function getTouchPos(canvasDom, touchEvent) {
        const rect = canvasDom.getBoundingClientRect();
        return {
            x: touchEvent.touches[0].clientX - rect.left,
            y: touchEvent.touches[0].clientY - rect.top
        };
    }
    
    canvas.addEventListener('mousedown', (e) => {
        isDrawing = true;
        const pos = {
            x: e.clientX - canvas.getBoundingClientRect().left,
            y: e.clientY - canvas.getBoundingClientRect().top
        };
        drawStart(pos);
    });
    
    canvas.addEventListener('mousemove', (e) => {
        if (isDrawing) {
            const pos = {
                x: e.clientX - canvas.getBoundingClientRect().left,
                y: e.clientY - canvas.getBoundingClientRect().top
            };
            drawMove(pos);
        }
    });
    
    canvas.addEventListener('mouseup', () => {
        isDrawing = false;
        updateHiddenInput();
    });
    
    // Touch events
    canvas.addEventListener('touchstart', (e) => {
        e.preventDefault();
        isDrawing = true;
        const pos = getTouchPos(canvas, e);
        drawStart(pos);
    });
    
    canvas.addEventListener('touchmove', (e) => {
        e.preventDefault();
        if (isDrawing) {
            const pos = getTouchPos(canvas, e);
            drawMove(pos);
        }
    });
    
    canvas.addEventListener('touchend', () => {
        isDrawing = false;
        updateHiddenInput();
    });
    
    function drawStart(pos) {
        ctx.beginPath();
        ctx.moveTo(pos.x, pos.y);
    }
    
    function drawMove(pos) {
        ctx.lineTo(pos.x, pos.y);
        ctx.stroke();
    }
    
    function clearSignature() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        updateHiddenInput();
    }
    
    function updateHiddenInput() {
        const signatureImage = canvas.toDataURL();
        document.getElementById('esign').value = signatureImage;
    }
    </script>
    {{-- !here i change  --}}
    <script>
        function showContactInput() {
            var specialty = document.getElementById("specialty");
            var otherInput = document.getElementById("otherInput");
    
            if (specialty.value === "Other") {
                otherInput.style.display = "block";
            } else {
                otherInput.style.display = "none";
            }
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var selectElement = document.getElementById('HcpDesignationSelect');
            var inputDiv = document.querySelector('.others-input');
    
            selectElement.addEventListener('change', function() {
                if (this.value === 'Others') {
                    inputDiv.style.display = 'block';
                } else {
                    inputDiv.style.display = 'none';
                }
            });
    
            // Show input box if 'Others' is pre-selected
            if (selectElement.value === 'Others') {
                inputDiv.style.display = 'block';
            }
        });
    </script>
@endsection
