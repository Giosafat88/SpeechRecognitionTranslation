<?php
// Initialize session for save the destination language value for text-to-speech.
session_start();
include 'HttpTranslator.php';
include 'AccessTokenAuthentication.php';
include 'credential.php';

// Verify if there is a previous speech file.
if (glob('./speech_file/*.mp3'))
{
    $file = glob('speech_file/'.'*.mp3',GLOB_MARK);
    foreach ($file as $file)
    {
        if (is_dir($file))
        {
            self::deleteDir($file);
        }
        else
        {
            unlink($file);
        }
    }
}
	//verify the click of button_translate
  if (isset($_POST['button_translate']))
    {

		

 				  
    	// Sets session variable for text_to_speech
		if (isset($_COOKIE['cookie_text_to_speech']))
    {	
	$finaltranscript =$_COOKIE['cookie_text_to_speech'];
	
        $_SESSION['final'] = $_COOKIE['cookie_text_to_speech'];


    }  
	// Sets session variable for source lang
    if (isset($_COOKIE['cookie_source']))
    {
	$source =$_COOKIE['cookie_source'] ;
        $_SESSION['source'] = $_COOKIE['cookie_source'];

	
    }

	// Sets session variable for destination lang
    if (isset($_COOKIE['cookie_dest']))
    {

	$dest =$_COOKIE['cookie_dest'] ;
        $_SESSION['dest'] = $_COOKIE['cookie_dest'];

    }
	
	// Sets session variable for index of source lang
	if (isset($_COOKIE['cookie_index_sourcelang']))
    {

	$source_index =$_COOKIE['cookie_index_sourcelang'] ;
        $_SESSION['source_index'] = $_COOKIE['cookie_index_sourcelang'];

    }
	
	// Sets session variable for index of destination lang
	if (isset($_COOKIE['cookie_index_destlang']))
    {

	$dest_index =$_COOKIE['cookie_index_destlang'] ;
        $_SESSION['dest_index'] = $_COOKIE['cookie_index_destlang'];

    }
	
	// Sets session variable for index of source dialect
	if (isset($_COOKIE['cookie_index1_dialect']))
    {

	$dialect1_index =$_COOKIE['cookie_index1_dialect'] ;
        $_SESSION['dialect1_index'] = $_COOKIE['cookie_index1_dialect'];

    }
	
	// Sets session variable for index of destination dialect
	if (isset($_COOKIE['cookie_index2_dialect']))
    {

	$dialect2_index =$_COOKIE['cookie_index2_dialect'] ;
        $_SESSION['dialect2_index'] = $_COOKIE['cookie_index2_dialect'];

    }


   
if('POST' == $_SERVER['REQUEST_METHOD'])
{    
    try 
    {
        // Create the AccessTokenAuthentication object.
        $authObj = new AccessTokenAuthentication();
        // Get the Access token.
        $accessToken = $authObj -> getTokens($grantType, $scopeUrl, $clientID, $clientSecret, $authUrl);
        // Create the authorization Header string.
        $authHeader = "Authorization: Bearer ". $accessToken;
        
		
		// Set the parameters.
        // Sets source language. $fromLanguage 
        $fromLanguage =$_COOKIE['cookie_source'];
		
        // Sets destination language. $toLanguage 
        $toLanguage = $_COOKIE['cookie_dest'];
        // Sets text to translate. $inputStr 
        $inputStr = $_COOKIE['cookie_text_to_speech'];
		// Sets index of source_lang. $source_index
		$source_index= $_COOKIE['cookie_index_sourcelang'];
		// Sets index of dest_lang. $dest_index
		$dest_index= $_COOKIE['cookie_index_destlang'];
		// Sets index of dialect_source_lang. $dialect1_index
		$dialect1_index=$_COOKIE['cookie_index1_dialect'];
		// Sets index of dialect_dest_lang. $dialect2_index
		$dialect2_index=$_COOKIE['cookie_index2_dialect'];
		
		
		$contentType = 'text/plain';
        $category = 'general';
        
        // Variable that composes the string of parameters for the transaltion
        $paramst = "text=".urlencode($inputStr)."&to=".$toLanguage."&from=".$fromLanguage;
        // URL to translate the text
        $translateUrl = "http://api.microsofttranslator.com/v2/Http.svc/Translate?$paramst";
    
        //Create the Translator Object.
        $translatorObj = new HTTPTranslator();
    
        //Get the curlResponse.
        $curlResponse = $translatorObj -> curlRequest($translateUrl, $authHeader);
    
        //Interprets a string of XML into an object.
        $xmlObj = simplexml_load_string($curlResponse);
        foreach((array)$xmlObj[0] as $val)
        {
            $translatedStr = $val;
        }


        $translatedText = urlencode($translatedStr);



 $out = 'audio/mp3';
        $params = "text=$translatedText&language=$toLanguage&format=$out";

        //HTTP Speak method URL.
        $url = "http://api.microsofttranslator.com/V2/Http.svc/Speak?$params";

        $translatorObj = new HTTPTranslator();

        $strResponse = $translatorObj -> curlRequest($url, $authHeader);
        
        //Create a fold to insert a speech file generated if not exists.
        if (!is_dir('speech_file'))
        {
            mkdir('speech_file');
        }
        
        //Create the name of speech file.
        $var = uniqid('SPC_').".mp3";
		$var1 = urlencode($var);

        //Save file into server directory.
        file_put_contents('./speech_file/'.$var1, $strResponse);

	}
catch (Exception $e) 
      {
          echo "Exception: ".$e->getMessage().PHP_EOL;
      }

}
}
?>
<!DOCTYPE html>
<html class="no-js consumer" lang="en">
  <head>
  <link rel="stylesheet" href="css.css" type="text/css">
    <script>
(function(e, p){
    var m = location.href.match(/platform=(win8|win|mac|linux|cros)/);
    e.id = (m && m[1]) ||
           (p.indexOf('Windows NT 6.2') > -1 ? 'win8' : p.indexOf('Windows') > -1 ? 'win' : p.indexOf('Mac') > -1 ? 'mac' : p.indexOf('CrOS') > -1 ? 'cros' : 'linux');
    e.className = e.className.replace(/\bno-js\b/,'js');
  })(document.documentElement, window.navigator.userAgent)
    </script>
    <meta charset="utf-8">
    <meta content="initial-scale=1, minimum-scale=1, width=device-width" name="viewport">
    <meta content=
    "Google Chrome is a browser that combines a minimal design with sophisticated technology to make the web faster, safer, and easier."
    name="description">
    <title>
     Progetto Speech Recognition and Translation
    </title>
    <link href="https://plus.google.com/100585555255542998765" rel="publisher">
    <link href="//www.google.com/images/icons/product/chrome-32.png" rel="icon" type="image/ico">
    <link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&amp;subset=latin" rel=
    "stylesheet">
    
    <script src="//www.google.com/js/gweb/analytics/autotrack.js">
</script>
    <script>
new gweb.analytics.AutoTrack({
          profile: 'UA-26908291-1'
        });
    </script>
   
  </head>
  <body class="" id="grid">
    <div class="browser-landing" id="main">
      <div class="compact marquee-stacked" id="marquee">
        <div id = "header">
           
        </div>
        </div>
      </div>
      <div class="compact marquee">
        <div id="info">
         
          <p id="info_no_speech" style="display:none">
		  
	   Nessun riconoscimento vocale è stato trovato. Controlla le impostazioni del microfono<a href=
            "//support.google.com/chrome/bin/answer.py?hl=en&amp;answer=1407892">microphone settings</a>.
          
		  </p>
          <p id="info_no_microphone" style="display:none">
		  
	    Nessun microfono è stato trovato. Assicurati che sia installato<a href="//support.google.com/chrome/bin/answer.py?hl=en&amp;answer=1407892">
            microphone settings</a> are configured correctly.
          
		  </p>
           <p id="info_allow" style="display:none">
	    Clicca "Consenti" per abilitare il microfono.
          </p>
          <p id="info_denied" style="display:none">
           Negato permesso utilizzo microfono. 
          </p>
          <p id="info_blocked" style="display:none">
          Permesso di utilizzo microfono bloccato. Per cambiare, vai alla pagina://settings/contentExceptions#media-stream
          </p>
          <p id="info_upgrade" style="display:none">
	    Web Speech Api non è supportato da questo browser. Effettua l'upgrade<a href=
            "//www.google.com/chrome">Chrome</a> alla versione 25 o dopo.
          </p> 
        </div>
        <div id="container">
		<div id="div_start">
		
          <button id="start_button" class="start_button" type="button" onclick="startButton(event)"></button>
        </div>
		<div id="animato">
		
		<button id="animate" class="animate" type="button" style="display:none" onclick="stop_rec()" >
		</button>
		</div>
        <div id="results">
          <span class="final" id="final_span"></span> <span class="interim" id=
          "interim_span"></span>
		   <?php if (isset($inputStr)== true){echo $inputStr;}else{echo '';} ?>
        </div>
		
        <div id="results2">
         <span class="final" id="final_span2"></span> </span>
		  <?php if (isset($inputStr)== true){echo $translatedStr;}else{echo '';} ?>
        </div>
        
	



<form id="trad" name='trad' action= "index.php" method= "POST">      
       <!-- Declare list box of source language and source dialect-->
		<div class="compact marquee" id="div_language">
          <select id="select_language" onchange="updateCountry()">
		  
            </select>
			&nbsp;&nbsp; <select id="select_dialect">
            </select>
        </div>
		

        <!-- Text-to-speech's button. -->
        <button id = "t2s" class = "t2s" type = "button"  onclick = "speech_play()"></button>

		<!-- Button reset-->
		<button id = "reset" class="reset" type"button" onclick = "javascript:result.final_transcript.value = ''; javascript:results2.value = ''">Reset</button>
		
		
		 <!-- Declare list box of destination language and dest dialect-->
		<div class="compact marquee" id="div_language">
          <select id="select_language2" onchange="updateCountry2()">
            </select>&nbsp;&nbsp; <select id="select_dialect2">
            </select>
        </div>
		 </div>
		
		
      </div>
		</div>


  <!-- Button translate-->
  <div id="container">
	<div id="copy">
	
          <button id="buttons" name="button_translate" onclick="Translate()" value="Translate">Traduci</button>
	</div>		  
		 </div>	
<script>
var source='';
var dest='';
var final_transcript='';

 window.___gcfg = { lang: 'en' };
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
 
  
    
     
    </script> <script>
var langs =
[
 
 
 
 ['German',         ['de']],
 ['English',         ['en', 'Australia'],
                     ['en', 'Canada'],
                     ['en', 'India'],
                     ['en', 'New Zealand'],
                     ['en', 'South Africa'],
                     ['en', 'United Kingdom'],
                     ['en', 'United States']],
 ['Español',         ['es', 'Argentina'],
                     ['es', 'Bolivia'],
                     ['es', 'Chile'],
                     ['es', 'Colombia'],
                     ['es', 'Costa Rica'],
                     ['es', 'Ecuador'],
                     ['es', 'El Salvador'],
                     ['es', 'España'],
                     ['es', 'Estados Unidos'],
                     ['es', 'Guatemala'],
                     ['es', 'Honduras'],
                     ['es', 'México'],
                     ['es', 'Nicaragua'],
                     ['es', 'Panamá'],
                     ['es', 'Paraguay'],
                     ['es', 'Perú'],
                     ['es', 'Puerto Rico'],
                     ['es', 'República Dominicana'],
                     ['es', 'Uruguay'],
                     ['es', 'Venezuela']],
 
 ['Français',        ['fr']],


 ['Italiano',        ['it', 'Italia'],       
                     ['it', 'Svizzera']],

 ['Nederlands',      ['nl']],

 ['Polski',          ['pl']],
 ['Português',       ['pt', 'Brasil'],
                     ['pt', 'Portugal']],
 ['Română',          ['ro']],

 ['Svenska',         ['sv']],
 ['Türkçe',          ['tr']],

 ['Pусский',         ['ru']],


['한국어',            ['ko']],
 ['中文',             ['zh', '普通话 (中国大陆)'],
                     ['zh', '普通话 (香港)'],
                     ['zh', '中文 (台灣)'],
                     ['zh', '粵語 (香港)']],
 ['日本語',           ['ja']]];

 for (var i = 0; i < langs.length; i++) {
  select_language.options[i] = new Option(langs[i][0], i);
select_language2.options[i] = new Option(langs[i][0], i);
}
verify();

updateCountry();
updateCountry2();


var translated_string;

//evalue the list box with the selected language and dialect, otherwise whit a defalult index
function verify()
{<?php if (isset($source_index) == true) 
                    { 
					?> 
					var source_index = "<?php echo $source_index; ?>";
					var dest_index= "<?php echo $dest_index; ?>";
					
					select_language.selectedIndex = source_index;
					select_language2.selectedIndex = dest_index;
					
					<?php 
					} else {?> 
					select_language.selectedIndex = 4;
					select_language2.selectedIndex = 1;
					select_dialect.selectedIndex = 0;
					select_dialect2.selectedIndex = 6;
					<?php }?> 
					}

  <!-- Depending of the source language choosen, evalue the corresponding dialect-->					
function updateCountry() {
                       
 for (var i = select_dialect.options.length - 1; i >= 0; i--) {
   select_dialect.remove(i);
 }
 var list = langs[select_language.selectedIndex];
 for (var i = 1; i < list.length; i++) {
   select_dialect.options.add(new Option(list[i][1], list[i][0]));
 } 
 <?php if (isset($dialect1_index) == true) 
                   { 
                                       ?> 
                                       var dialect1_index= "<?php echo $dialect1_index; ?>";
                                       select_dialect.selectedIndex=dialect1_index;
               <?php } else {?>
 <?php }?>
 select_dialect.style.visibility = list[1].length == 1 ? 'hidden' : 'visible';
}


  <!-- Depending of the destination language choosen, evalue the corresponding dialect-->		
function updateCountry2() {
  for (var i = select_dialect2.options.length - 1; i >= 0; i--) {
    select_dialect2.remove(i);
  }
  var list = langs[select_language2.selectedIndex];
  for (var i = 1; i < list.length; i++) {
    select_dialect2.options.add(new Option(list[i][1], list[i][0]));
  }
  <?php if (isset($dialect2_index) == true) 
                   { 
                                       ?> 
                                       var dialect2_index= "<?php echo $dialect2_index; ?>";
                                       
                                       select_dialect2.selectedIndex=dialect2_index;
               <?php } else {?>
 <?php }?>
  select_dialect2.style.visibility = list[1].length == 1 ? 'hidden' : 'visible';
}

var recognizing = false;
var ignore_onend;
var start_timestamp;
var current_style;

if (!('webkitSpeechRecognition' in window)) {
  upgrade();
} else {
  start_button.style.display = 'inline-block';
  buttons.style.display = 'none';
  
  var recognition = new webkitSpeechRecognition();
  recognition.continuous = true;
  recognition.interimResults = true;
  recognition.onstart = function() {
    recognizing = true;
    start_button.style.display = 'none';
	animate.style.display = 'inline-block';

	
  };
  recognition.onerror = function(event) {
    if (event.error == 'no-speech') {
      start_img.src = 'image/mic.gif';
      showInfo('info_no_speech');
      ignore_onend = true;
    }
    if (event.error == 'audio-capture') {
      start_img.src = 'image/mic.gif';
      showInfo('info_no_microphone');
      ignore_onend = true;
    }
    if (event.error == 'not-allowed') {
      if (event.timeStamp - start_timestamp < 100) {
        showInfo('info_blocked');
      } else {
        showInfo('info_denied');
      }
      ignore_onend = true;
    }
  };
  
  recognition.onend = function() {
    recognizing = false;
    if (ignore_onend) {
      return;
    }
    start_img.src = 'image/mic.png';
    if (!final_transcript) {
      
      return;
    }
    showInfo('');
    if (window.getSelection) {
      window.getSelection().removeAllRanges();
      var range = document.createRange();
      range.selectNode(document.getElementById('final_span'));
      window.getSelection().addRange(range);
    }
   
  };
  recognition.onresult = function(event) {
    var interim_transcript = '';
    if (typeof(event.results) == 'undefined') {
      recognition.onend = null;
      recognition.stop();
      upgrade();
      return;
    }
    for (var i = event.resultIndex; i < event.results.length; ++i) {
      if (event.results[i].isFinal) {
        final_transcript += event.results[i][0].transcript;
      } else {
        interim_transcript += event.results[i][0].transcript;
      }
    }
    final_transcript = capitalize(final_transcript);
 
    final_span.innerHTML = linebreak(final_transcript);
    interim_span.innerHTML = linebreak(interim_transcript);
    if (final_transcript || interim_transcript) {
      showButtons('inline-block');

    }
  };
}


function stop_rec() {

recognition.stop();
animate.style.display= 'none';
start_button.style.display= '';

}

function startButton(event) {
  if (recognizing) {
    recognition.stop();
    return;
  }
  recognition.lang = select_dialect.value;
  recognition.start();
  ignore_onend = false;
  final_span.innerHTML = '';
  interim_span.innerHTML = '';
  start_img.src = 'image/mic-slash.gif';
  showInfo('info_allow');
  showButtons('none');
  start_timestamp = event.timeStamp;
}
 
function upgrade() {
  start_button.style.visibility = 'hidden';
  showInfo('info_upgrade');
}
var two_line = /\n\n/g;
var one_line = /\n/g;
function linebreak(s) {
  return s.replace(two_line, '<p></p>').replace(one_line, '');
}
var first_char = /\S/;
function capitalize(s) {
  return s.replace(first_char, function(m) { return m.toUpperCase(); });
}

function Translate() {
  if (recognizing) {
    recognizing = false;
    recognition.stop();
//set variable for the trasmission to the server 
  	

text_to_speech=final_transcript;
index_sourcelang= select_language.selectedIndex;
index_destlang= select_language2.selectedIndex;
index1_dialect= select_dialect.selectedIndex;
index2_dialect=select_dialect2.selectedIndex;
document.cookie='cookie_index2_dialect='+index2_dialect;
document.cookie= 'cookie_index_sourcelang='+index_sourcelang;
document.cookie= 'cookie_index_destlang='+index_destlang;
document.cookie= 'cookie_text_to_speech='+text_to_speech;
document.cookie= 'cookie_index1_dialect='+index1_dialect;
source=langs[select_language.selectedIndex][1][0];
document.cookie= 'cookie_source='+source;
dest=langs[select_language2.selectedIndex][1][0];
document.cookie= 'cookie_dest='+dest;

final_span.innerHTML=linebreak=linebreak(final_transcript);
buttons.style.display = style;

translated_string='<?php if (isset($translatedStr)== true){echo $translatedStr;}else{echo '';} ?>';

     }
else{
}
  
  copy_info.style.display = 'inline-block';
  showInfo('');
}



function showInfo(s) {
  if (s) {
    for (var child = info.firstChild; child; child = child.nextSibling) {
      if (child.style) {
        child.style.display = child.id == s ? 'inline' : 'none';
      }
    }
    info.style.visibility = 'visible';
  } else {
    info.style.visibility = 'hidden';
  }
}


function showButtons(style) {
  if (style == current_style) {
    return;
  }
  current_style = style;
  buttons.style.display = style;
  
  copy_button.style.display = style;
  
  copy_info.style.display = 'none';
 
}


function speech_play()
 {

     var play_speech = document.getElementById("play_speech");
     if (play_speech.paused) 
     {
         play_speech.play();
     }
     else 
     {
         play_speech.pause();
     }
 }
 
<!-- PHP's script for read the name of the speech file. -->
        <?php foreach(glob('./speech_file/*.*') as $filename)
              {
                  $speech = $filename;
			
              }
        ?>
</script>
    <!-- Player for listen the text-to-speech. -->
        <audio id = "play_speech">
        	<source src = "<?php echo $speech; ?>" type = "audio/mp3" />
        </audio>    
		



</form>
<div id = "pagef">
            
        </div>
  </body>
</html>