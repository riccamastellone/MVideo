<!-- SCREEN BRIGHTNESS -->
<div class="modal fade" id="brightness-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Screen Brightness</h4>
            </div>
            <div class="modal-body">
                <p>Do you want to change the default step percentage?</p>
                <div class="input-group">
                    <input type="number" class="form-control" id="brightness-step" value="25">
                    <span class="input-group-addon">%</span>
                </div>
            </div>
        </div>
    </div>
</div>
    
<!-- WIFI STRENGHT -->
<div class="modal fade" id="wifi-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Wifi Signal Strenght</h4>
            </div>
            <div class="modal-body">
                <p>Do you want to change the default step percentage?</p>
                <div class="input-group">
                    <input type="number" class="form-control" id="wifi-step" value="25">
                    <span class="input-group-addon">%</span>
                </div>
            </div>
        </div>
    </div>
</div>
    
<!-- VOLUME -->
<div class="modal fade" id="audio-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Volume Level</h4>
            </div>
            <div class="modal-body">
                <p>Do you want to change the default step percentage?</p>
                <div class="input-group">
                    <input type="number" class="form-control" id="audio-step" value="25">
                    <span class="input-group-addon">%</span>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- VIDEO INPUT -->
<div class="modal fade" id="input-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Video source</h4>
            </div>
            <div class="modal-body">
                <p>Paste here the link to the video you want to test!</p>
                <input type="text" class="form-control">
            </div>
	    <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		<button type="button" class="btn btn-primary" onclick="processUrl()">Save</button>
	    </div>
        </div>
    </div>
</div>
 
<!-- MAX VIDEO LENGHT -->
<div class="modal fade" id="length-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Max played video length</h4>
            </div>
            <div class="modal-body">
                <p>How many hours/minutes of the video you want to play at most? <br>
		Please note that if the video is shorted than the time you set, this setting
		will not be respected</p>
		<div class="time-container row">
		    <div class="col-sm-6">
			<div class="form-group">
			<label>Hours</label>
			<select class="form-control" name="length-hours">
			<?php

			    for( $i = 0; $i <= 12 ; $i++ ) {
				echo "<option>";
				echo $i < 10 ? '0'.$i : $i;
				echo "</option>";
			    }

			?>
			</select>
			</div>
		    </div>
		    <div class="col-sm-6">
			<div class="form-group">
			<label>Minutes</label>
			<select class="form-control" name="length-minutes">
			<?php

			    for( $i = 0; $i <= 59 ; $i++ ) {
				$selected = $i == 15 ? 'selected="selected"' : '';
				echo "<option $selected >";
				echo $i < 10 ? '0'.$i : $i;
				echo "</option>";
			    }

			?>
			</select>
			</div>
		    </div>
		</div>
            </div>
	    .
        </div>
    </div>
</div>