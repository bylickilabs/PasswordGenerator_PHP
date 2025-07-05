<?php

// 

// --- PHP: Passwort-Generator Backend ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    $len = isset($_POST['length']) ? max(8, min(40, intval($_POST['length']))) : 16;
    $cset = '';
    if (!empty($_POST['lowercase'])) $cset .= 'abcdefghijklmnopqrstuvwxyz';
    if (!empty($_POST['uppercase'])) $cset .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    if (!empty($_POST['numbers']))   $cset .= '0123456789';
    if (!empty($_POST['symbols']))   $cset .= '!@#$%^&*()-_+=[]{}:;,.?/|~<>';
    if ($cset === '') $cset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $pw = '';
    for ($i = 0; $i < $len; ++$i) $pw .= $cset[random_int(0, strlen($cset) - 1)];
    echo json_encode(['password' => $pw]);
    exit;
}
?>


<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Passwort Generator</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
	    
	<!-- Orbitron Font -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&display=swap" rel="stylesheet">
    <style>
        html, body { height: 100%; }
        body {
            font-family: 'Orbitron', Arial, sans-serif;
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;
            background: linear-gradient(-45deg, #14f1ff, #d414ff, #00ffb2, #2929ff, #0d1437, #14f1ff);
            background-size: 400% 400%;
            animation: animatedGradient 16s ease-in-out infinite;
        }
        @keyframes animatedGradient {
            0% { background-position: 0% 50%; }
            25% { background-position: 50% 100%; }
            50% { background-position: 100% 50%; }
            75% { background-position: 50% 0%; }
            100% { background-position: 0% 50%; }
        }

        .github-fix {
            position: fixed; top: 34px; right: 45px; z-index: 99;
            width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;
            border-radius: 50%;
            background: linear-gradient(135deg, #14f1ff 0%, #d414ff 100%);
            box-shadow: 0 0 18px 3px #14f1ffcc, 0 0 48px 16px #d414ff44;
            border: 3.2px solid #fff;
            cursor: pointer;
            transition: box-shadow 0.18s, transform 0.16s;
        }
        .github-fix svg {
            width: 34px; height: 34px; fill: #fff;
            filter: drop-shadow(0 0 7px #14f1ffcc);
        }
        .github-fix:hover { box-shadow: 0 0 60px #14f1ffcc, 0 0 110px #d414ff99; transform: scale(1.12);}
        .github-fix:active { transform: scale(0.92);}

        .container {
            background: rgba(18, 24, 35, 0.97);
            max-width: 850px;
            width: 97vw;
            margin: 5vw auto 0 auto;
            border-radius: 1.7rem;
            box-shadow: 0 0 44px 7px #14f1ff77;
            padding: 2.7rem 2.7rem 2.7rem 2.7rem;
            display: flex; flex-direction: column; align-items: stretch;
            z-index: 2; position: relative;
        }
        .neon-border {
            border: 3.2px solid #14f1ff;
            box-shadow: 0 0 16px #0ff, 0 0 40px #14f1ff99 inset;
        }
        h1.neon-text {
            color: #14f1ff;
            text-shadow: 0 0 6px #0ff, 0 0 13px #14f1ff, 0 0 35px #d414ff88;
            text-align: center;
            font-size: 2.15rem;
            margin-bottom: 2.1rem;
            letter-spacing: 0.13em;
            white-space: nowrap;
        }
        .row {
            display: flex; gap: 1.55rem; align-items: center; margin-bottom: 1.45rem; width: 100%;
        }
        .pw-out {
            flex: 1;
            background: #191e2b; border: none;
            border-radius: 0.83rem;
            color: #14f1ff; font-size: 1.18rem; padding: 1.05rem 1.18rem;
            box-shadow: 0 0 13px #14f1ff66 inset;
            outline: none; font-family: inherit; letter-spacing: 0.025em;
        }
        .pw-out:focus { box-shadow: 0 0 21px #0ff88b, 0 0 18px #14f1ffcc inset;}
        .copy-btn {
            background: linear-gradient(90deg, #14f1ff 0%, #d414ff 100%);
            color: #fff;
            border: none;
            min-width: 121px; min-height: 51px;
            padding: 0.91rem 1.5rem;
            border-radius: 0.85rem;
            box-shadow: 0 0 14px #14f1ffcc, 0 0 22px #d414ff44;
            font-size: 1.11rem; font-family: inherit; font-weight: 700; letter-spacing: 0.03em;
            cursor: pointer; transition: transform 0.14s, box-shadow 0.14s;
            position: relative;
        }
        .copy-btn:hover { transform: scale(1.06); box-shadow: 0 0 22px #14f1ffcc, 0 0 28px #d414ff;}
        .copy-btn:active { transform: scale(0.96);}
        .copy-btn.copied { background: linear-gradient(90deg, #1dd960 0%, #42e9fa 100%);}
        .copy-btn .copy-label, .copy-btn .copy-done {
            position: absolute; left: 50%; top: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
            font-size: 1.01rem; font-weight: 600;
            letter-spacing: 0.02em;
            text-shadow: 0 0 6px #14f1ff99, 0 0 8px #d414ff55;
            transition: opacity 0.28s;
        }
        .copy-btn .copy-done { opacity: 0;}
        .copy-btn.copied .copy-label { opacity: 0;}
        .copy-btn.copied .copy-done { opacity: 1;}
        .pw-strength {
            margin-left: 0.7rem;
            font-size: 1.01rem;
            font-weight: 700;
            letter-spacing: 0.02em;
        }
        .pw-strength .stark { color: #33ff80; text-shadow: 0 0 8px #1dffbb88;}
        .pw-strength .mittel { color: #ffe600; text-shadow: 0 0 7px #ffd60077;}
        .pw-strength .schwach { color: #ff5577; text-shadow: 0 0 7px #ff255577;}

        /* --- Animierter Slider --- */
        .slider-wrap {
            margin: 0.5rem 0 1.3rem 0; width: 100%; height: 54px; position: relative;
            display: flex; align-items: flex-end; justify-content: flex-start;
        }
        .slider-label {
            color: #0ff; font-size: 1.09rem; font-weight: 700; letter-spacing: 0.02em;
            position: absolute; top: -1.3em; left: 0;
        }
        .slider-container {
            flex: 1 1 0; position: relative; height: 44px; display: flex; align-items: center;
        }
        #len {
            width: 100%; height: 15px; background: transparent;
            accent-color: #14f1ff; position: relative; z-index: 1;
            cursor: pointer;
            /* Entfernt Standard-Styling */
            -webkit-appearance: none; appearance: none; outline: none;
        }
        #len::-webkit-slider-runnable-track {
            height: 13px;
            background: linear-gradient(90deg, #14f1ff 0%, #d414ff 100%);
            border-radius: 8px;
            box-shadow: 0 0 14px #14f1ff88, 0 0 32px #d414ff44;
        }
        #len::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 32px; height: 32px;
            background: radial-gradient(circle at 40% 40%, #fff 0 38%, #14f1ff 68%, #d414ff 100%);
            border-radius: 50%;
            box-shadow: 0 0 18px 6px #14f1ff99, 0 0 32px 13px #d414ff77;
            border: 3px solid #fff;
            cursor: pointer;
            transition: box-shadow 0.2s, background 0.3s;
            position: relative; z-index: 2;
        }
        #len:focus::-webkit-slider-thumb {
            background: radial-gradient(circle at 70% 70%, #fff 0 28%, #d414ff 60%, #14f1ff 100%);
            box-shadow: 0 0 27px 12px #d414ffbb, 0 0 44px 23px #14f1ff66;
        }
        #len::-ms-fill-lower, #len::-ms-fill-upper {
            background: linear-gradient(90deg, #14f1ff 0%, #d414ff 100%);
            border-radius: 8px;
        }
        #len::-moz-range-thumb {
            width: 32px; height: 32px;
            background: radial-gradient(circle at 40% 40%, #fff 0 38%, #14f1ff 68%, #d414ff 100%);
            border-radius: 50%;
            box-shadow: 0 0 18px 6px #14f1ff99, 0 0 32px 13px #d414ff77;
            border: 3px solid #fff;
            cursor: pointer;
            transition: box-shadow 0.2s, background 0.3s;
        }
        #len:focus::-moz-range-thumb {
            background: radial-gradient(circle at 70% 70%, #fff 0 28%, #d414ff 60%, #14f1ff 100%);
            box-shadow: 0 0 27px 12px #d414ffbb, 0 0 44px 23px #14f1ff66;
        }
        #len::-webkit-slider-thumb { margin-top: -10px; }
        #len::-ms-tooltip { display: none; }
        /* --- Länge-Anzeige direkt über dem Thumb --- */
        .slider-val {
            position: absolute;
            top: -2.6em; left: 0;
            background: #292c41;
            color: #d414ff;
            padding: 0.23em 1.05em 0.17em 1.05em;
            border-radius: 1.18em;
            font-size: 1.04rem;
            font-weight: 700;
            box-shadow: 0 0 14px #d414ff44, 0 0 18px #14f1ff33;
            pointer-events: none;
            transform: translateX(-50%);
            transition: left 0.22s cubic-bezier(.8, .2, .25, 1), box-shadow 0.22s, background 0.22s, color 0.16s;
            z-index: 5;
        }
        .slider-val.slider-anim {
            box-shadow: 0 0 28px #14f1ffbb, 0 0 37px #d414ff99;
            background: #14f1ff;
            color: #222;
        }
        .pw-form {
            margin-top: 1.8rem;
            display: flex; flex-direction: row; gap: 1.7rem; align-items: center; flex-wrap: wrap;
        }
        .pw-form label {
            color: #00ffb2; font-weight: 700; font-size: 1.07rem; margin-right: 1.1rem;
            cursor: pointer; user-select: none; text-shadow: 0 0 10px #00ffb255, 0 0 4px #d414ff88;
            display: flex; align-items: center; gap: 0.43em;
        }
        .pw-form input[type="checkbox"] {
            accent-color: #fff; width: 18px; height: 18px; border-radius: 4px; margin-right: 4px;
        }
        .generate-btn {
            min-width: 160px;
            width: 100%;
            margin-top: 2.2rem;
            font-size: 1.20rem;
            font-weight: 900;
            letter-spacing: 0.09em;
            padding: 1.13rem 0;
            border-radius: 0.85rem;
            box-shadow: 0 0 32px 4px #14f1ff55, 0 0 60px 9px #d414ff22;
            background: linear-gradient(90deg, #14f1ff 0%, #d414ff 100%);
            transition: box-shadow 0.22s, background 0.33s;
            display: block;
        }
        .generate-btn:hover { background: linear-gradient(90deg, #d414ff 0%, #14f1ff 100%); box-shadow: 0 0 44px 11px #14f1ff, 0 0 90px 18px #d414ff88;}
        .footer {
            position: fixed; left: 0; bottom: 0; width: 100vw;
            background: rgba(18, 18, 30, 0.96);
            border-top: 2.5px solid #14f1ff;
            box-shadow: 0 0 20px #14f1ff66, 0 0 60px #d414ff33;
            color: #14f1ff;
            font-size: 1.1rem;
            text-align: center;
            padding: 0.9rem 0; z-index: 99; letter-spacing: 0.09em;
            text-shadow: 0 0 12px #14f1ff99;
            user-select: none;
        }
        @media (max-width: 1080px) {
            .container { max-width: 99vw; padding: 1.5rem 0.6rem;}
        }
        @media (max-width: 700px) {
            .container { padding: 1rem 0.2rem;}
            .github-fix { top: 10px; right: 7px; width: 38px; height: 38px;}
            .github-fix svg { width: 19px; height: 19px;}
            h1.neon-text { font-size: 1.10rem;}
            .footer { font-size: 0.97rem;}
        }
        @media (max-width: 520px) {
            .pw-form { flex-direction: column; gap: 1.2rem; align-items: stretch;}
            .row { flex-direction: column; gap: 0.5rem;}
        }
    </style>
</head>
<body>

<script src="protect.js"></script>

<script>
(function(){var _0x1a3b=["\x72\x65\x74\x75\x72\x6E\x20\x66\x61\x6C\x73\x65","\x6F\x6E\x73\x65\x6C\x65\x63\x74\x73\x74\x61\x72\x74","\x6F\x6E\x6D\x6F\x75\x73\x65\x64\x6F\x77\x6E","\x6F\x6E\x63\x6C\x69\x63\x6B","\x73\x69\x64\x65\x62\x61\x72"];
function disableselect(_0x2929x2){return false;}function reEnable(){return true;}document[_0x1a3b[1]]=new Function(_0x1a3b[0]);if(window[_0x1a3b[4]]){document[_0x1a3b[2]]=disableselect;document[_0x1a3b[3]]=reEnable;}document.addEventListener
('contextmenu',function(e){e.preventDefault();});})();
</script>


<script>
(function(){
  document.addEventListener('\x6b\x65\x79\x64\x6f\x77\x6e',function(e){
    if((e['\x63\x74\x72\x6c\x4b\x65\x79']||e['\x6d\x65\x74\x61\x4b\x65\x79'])&&e['\x6b\x65\x79']['\x74\x6f\x4c\x6f\x77\x65\x72\x43\x61\x73\x65']()=== '\x73'){
      e['\x70\x72\x65\x76\x65\x6e\x74\x44\x65\x66\x61\x75\x6c\x74']();
    }
  });
})();
</script>



		
<!-- Fixiertes, großes Logo oben links, ohne Rahmen, abgerundete Ecken -->
<div id="logo-panel" style="position:fixed;top:34px;left:40px;z-index:100;">
  <img
    id="logo-img"
    src="assets/logo.png"
    alt="Logo"
    style="width:160px;height:160px;border-radius:42px;object-fit:cover;display:block;transition:width 0.23s, height 0.23s, border-radius 0.23s;">
</div>



    <!-- GitHub Social Icon -->
    <a class="github-fix" href="https://github.com/bylickilabs" target="_blank" title="GitHub">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 .297c-6.6 0-12 5.4-12 12 0 5.3 3.4 9.7 8.2 11.2.6.1.8-.3.8-.6v-2c-3.3.7-4-1.6-4-1.6-.5-1.2-1.1-1.6-1.1-1.6-.9-.6.1-.6.1-.6 1 .1 1.6 1 1.6 1 .9 1.6 2.4 1.1 3 .9.1-.7.3-1.1.6-1.4-2.6-.3-5.2-1.3-5.2-5.7 0-1.3.5-2.4 1.2-3.2-.1-.3-.5-1.6.1-3.2 0 0 1-.3 3.3 1.2a11.6 11.6 0 0 1 6 0c2.3-1.5 3.3-1.2 3.3-1.2.6 1.6.2 2.9.1 3.2.8.8 1.2 1.9 1.2 3.2 0 4.5-2.6 5.4-5.2 5.7.3.3.7.9.7 1.9v2.8c0 .3.2.7.8.6C20.6 22 24 17.6 24 12.3c0-6.6-5.4-12-12-12"></path></svg>
    </a>
	
	
    <!-- Overlay -->
    <div class="container neon-border">
        <h1 class="neon-text">Passwort Generator</h1>
        <div class="row">
            <input type="text" id="out" class="pw-out" readonly placeholder="Neues Passwort..." />
            <button class="copy-btn" id="copyBtn">
                <span class="copy-label">Kopieren</span>
                <span class="copy-done">Kopiert!</span>
            </button>
            <span class="pw-strength" id="pw-strength"></span>
        </div>
		
		
        <!-- Slider mit animierter Länge-Anzeige -->
        <div class="slider-wrap">
            <div class="slider-container">
                <input type="range" min="8" max="40" value="16" id="len" name="length" autocomplete="off" />
                <span class="slider-val" id="sliderVal">16</span>
            </div>
        </div>
        
		
		<!-- Generator-Form -->
        <form id="pw-form" class="pw-form" autocomplete="off">
            <label><input type="checkbox" name="lowercase" checked> Kleinbuchstaben</label>
            <label><input type="checkbox" name="uppercase" checked> Großbuchstaben</label>
            <label><input type="checkbox" name="numbers" checked> Zahlen</label>
            <label><input type="checkbox" name="symbols" checked> Sonderzeichen</label>
            <button type="submit" class="generate-btn">Generieren</button>
        </form>
    </div>
    <div class="footer">05/07/2025 - 11:30 Uhr | ©Thorsten Bylicki | ©BYLICKILABS – PHP Passwort Generator. All rights reserved.</div>
	

    <script>
        
		
		// --- Animierte Slider-Länge über Thumb ---
        const slider = document.getElementById('len');
        const sliderVal = document.getElementById('sliderVal');
        function updateSliderLabel(anim) {
            
			
			// Position des Thumb berechnen
            let min = parseInt(slider.min), max = parseInt(slider.max), val = parseInt(slider.value);
            let percent = (val - min) / (max - min);
            let trackWidth = slider.offsetWidth - 30;
            let pos = percent * trackWidth + 15;
            sliderVal.textContent = val;
            sliderVal.style.left = pos + 'px';
            if (anim) {
                sliderVal.classList.add('slider-anim');
                setTimeout(()=>sliderVal.classList.remove('slider-anim'), 370);
            }
        }
        slider.addEventListener('input', function() {
            updateSliderLabel(true);
        });
        window.addEventListener('resize', updateSliderLabel);
        updateSliderLabel();

        
		// --- Passwort-Generator: AJAX, Copy, Strength ---
        function pwStrength(pw) {
            if (!pw) return '';
            let hasLower = /[a-z]/.test(pw), hasUpper = /[A-Z]/.test(pw), hasNum = /\d/.test(pw), hasSym = /[^a-zA-Z0-9]/.test(pw);
            let score = [hasLower, hasUpper, hasNum, hasSym].filter(Boolean).length + (pw.length > 15 ? 1 : 0);
            if (score >= 4 && pw.length > 12) return '<span class="stark">Stark</span>';
            if (score >= 2) return '<span class="mittel">Mittel</span>';
            return '<span class="schwach">Schwach</span>';
        }
        function generatePW() {
            const form = document.getElementById('pw-form');
            const fd = new FormData(form);
            fd.set('length', slider.value);
            fetch(window.location.pathname, { method: 'POST', body: fd })
            .then(r => r.json()).then(data => {
                document.getElementById('out').value = data.password;
                document.getElementById('pw-strength').innerHTML = pwStrength(data.password);
            });
        }
        document.getElementById('pw-form').addEventListener('submit', function(e){
            e.preventDefault();
            generatePW();
        });
        
		
		// Initial PW
        window.addEventListener('DOMContentLoaded', function(){
            generatePW();
            updateSliderLabel();
        });

        
		// --- Copy ---
        const copyBtn = document.getElementById('copyBtn');
        copyBtn.addEventListener('click', function(){
            const val = document.getElementById('out').value;
            if(!val) return;
            navigator.clipboard.writeText(val);
            copyBtn.classList.add('copied');
            setTimeout(()=>copyBtn.classList.remove('copied'), 1100);
        });
</script>
	
		<script>
		function setLogoSize(size) {
		const img = document.getElementById('logo-img');
		img.style.width = size + "px";
		img.style.height = size + "px";
		img.style.borderRadius = (size / 3.8) + "px";
	}
	
    </script>
</body>
</html>
