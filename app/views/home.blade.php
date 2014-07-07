@extends('layouts.master')
    
@section('content')
    
<div class="jumbotron margin-top">
    <p>
	<a href="mailto:riccardo.mastellone@mail.polimi.it">Riccardo Mastellone</a> | 
	<a href="mailto:albifuma64@gmail.com">Alberto Fumagalli</a></p>
    <h1>MVideo</h1>
    <div class="current-status">
        <div id="wifi-signal" onclick="getWifi()"><span class="glyphicon glyphicon-signal"></span> <span class="value">70</span>%</div>
        <p>At the moment the phone is <span id="not-running"></span> running <strong id="popover" data-content="Blablablabla" role="button" data-placement="top" data-original-title="Current Test">this</strong> test and is <strong id="not-charging">not</strong> charging.</p>
        <p>It successfully completed <strong id="completed-tests">7</strong> out of <strong id="total-tests">12</strong> tests. You want to <a href="javascript:cancelQueue()">cancel</a> the queue?</p>
        <p>Otherwise you could <a href="javascript:newTest()">create some more</a></p>
    </div>
    <div id="new-test" class="margin-top">
        <div id="up-container">
            <h2>Step 1: Select video to test</h2>
            <div class="progress progress-striped active" style="display: none">
                <div class="progress-bar progress-bar-success"></div>
            </div>
	    <div class="input-buttons">
		<a id="up-button" class="btn btn-success btn-lg" role="button">Upload file</a>
		<a class="btn btn-success btn-lg video-input" role="button">Public URL</a>
		<a class="btn btn-success btn-lg video-input" role="button">YouTube video</a>
	    </div>
        </div>
        <div id="options" class="margin-top">
            <h2>Step 2: Let's select some options to test</h2>
            <p class='options'>
                <button type="button" id="brightness" class="btn btn-default btn-lg" onclick="toggleButton('brightness')">Screen Brightness <span class="glyphicon glyphicon-remove"></span></button>
                <button type="button" id="3g" class="btn btn-default btn-lg" onclick="toggleButton('3g')">3G Connectivity <span class="glyphicon glyphicon-remove"></span></button>
                <button type="button" id="wifi" class="btn btn-default btn-lg" <?= Config::get('mvideo.enable-wifi-levels') ? "onclick=\"toggleButton('wifi')\"" : 'disabled="disabled"'?>>Wifi Strenght <span class="glyphicon glyphicon-remove"></span></button>
                <button type="button" id="audio" class="btn btn-default btn-lg" onclick="toggleButton('audio')">Audio <span class="glyphicon glyphicon-remove"></span></button> 
                <!-- <button type="button" disabled="disabled" class="btn btn-default btn-lg">AV Bitrate <span class="glyphicon glyphicon-remove"></span></button> -->
		<button type="button" id="length" class="btn btn-default btn-lg" onclick="toggleButton('length')">Length <span class="glyphicon glyphicon-remove"></span></button>

                <input type="hidden" name="media">
            </p>
            <p>With these settings <strong id="t-count">1</strong> new tests will be queued</p>
            <p><button id="create-button" type="button" class="btn btn-success btn-lg" onclick="createTest()" data-loading-text="Saving..."></i>Create it!</button></p>
        </div>
    </div>
</div>
    
@include('includes.modals')
 
<script src="/packages/plupload/plupload.full.min.js"></script>
<script src="/js/upload.js"></script>
<script src="/js/home.js"></script>
@stop