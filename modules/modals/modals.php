<link rel="stylesheet" href="../modules/modals/modals.css">
<!-- ########################## PREVIEW ####################################### -->
<div class="rms-overlay" id="previewData">
	<div class="rms-dialog-inner" id="mydiv" style="overflow-y: scroll; min-width:550px;  max-height:85%">
		<div class="rms-modal-title drag" id="mydivheader" style="padding-left:15px;width:1350px;cursor:move">
			<span style="float:left"><i class="fa fa-bars"></i></span>
			<span style="text-align:left" id="previewData_title">PREVIEW DATA</span>
			<div class="rms-modal-close" onClick="closeDialoque('previewData');"><h4>&times</h4></div>
		</div>
		<div class="rms-modal-content" style="padding:10px 20px 10px 20px ">			
			<div id="previewData_page" style="width:100%;position:relative">Loading...</div>
			<div class="notification bg-warning results" id="notification"></div>
			<div id="note-container"></div>
		</div>
	</div>
</div>

<!-- ########################## UPLOAD DOCUMENTS ####################################### -->
<div class="rms-overlay" id="uploadDocuments">
	<div class="rms-dialog-inner" id="mydiv" style="overflow: hidden; min-width:250px; min-height:320px; max-width:900px; z-index:1">
		<div class="rms-modal-title drag" id="mydivheader" style="padding-left:15px;width:550px;cursor:move">
			<span style="float:left"><i class="fa fa-bars"></i></span>
			<span style="text-align:left" id="uploadDocuments_title">UPLOAD DOCUMENTS</span>
			<div class="rms-modal-close" onClick="closeDialoque('uploadDocuments');"><h4>&times</h4></div>
		</div>
		<div class="" style="padding:10px 20px 10px 20px ">			
			<iframe id="uploadDocuments_page" style="width:100%;height:320px;position:relative">Loading...</iframe>
		</div>
	</div>
</div>
<!-- ########################## VIEW SUBMITTED TO SERVER ####################################### -->
<div class="rms-overlay" id="viewserverdata">
	<div class="rms-dialog-inner" style="overflow-y: scroll; min-width:550px;  max-height:85%">
		<div class="rms-modal-title" style="padding-left:15px">
			<span style="float:left"><i class="fa fa-bars"></i></span>
			<span style="text-align:left" id="viewserverdata_title">SUBMITTED TO SERVER</span>
			<div class="rms-modal-close" onClick="closeDialoque('viewserverdata');"><h4>&times</h4></div>
		</div>
		<div class="rms-modal-content" style="padding:20px">			
			<div id="viewserverdata_page">				
			</div>
		</div>
	</div>
</div>
<!-- ########################## CHANGE SERVER ####################################### -->
<div class="rms-overlay" id="changeserverapp">
	<div class="rms-dialog-inner" style="overflow: hidden; min-width:350px; width:350px; max-height:100%">
		<div class="rms-modal-title" style="padding-left:15px">
			<span style="float:left"><i class="fa fa-bars"></i></span>
			<span style="text-align:left" id="changeserverapp_title">CHANGE SERVER</span>
			<div class="rms-modal-close" onClick="closeDialoque('changeserverapp');"><h4>&times</h4></div>
		</div>
		<div class="rms-modal-content" style="padding:20px">			
			<div id="changeserverapp_page">				
			</div>
		</div>
	</div>
</div>
<!-- ########################## APPLICATION INFORMATION ####################################### -->
<div class="rms-overlay" id="mrsform">
	<div class="rms-dialog-inner" style="overflow: hidden; width:450px; z-index:999">
		<div class="rms-modal-title" style="padding-left:15px">
			<span style="float:left"><i class="fa fa-bars"></i></span>
			<span style="text-align:left" id="createorder_title">MATERIAL REQUISITION SLIP</span>
			<div class="rms-modal-close" onClick="closeDialoque('mrsform');"><h4>&times</h4></div>
		</div>
		<div class="rms-modal-content" style="padding:20px">			
			<div id="mrsform_page">				
			</div>
		</div>
	</div>
</div>
<!-- ########################## APPLICATION INFORMATION ####################################### -->
<div class="rms-overlay" id="themes">
	<div class="rms-dialog-inner" style="overflow: hidden; width:; z-index:999">		
		<div class="rms-modal-content" style="padding:20px">			
			<div id="themes_page">Loading form... <i class="fa fa-spinner fa-spin"></i></div>
		</div>
	</div>
</div>
<!-- ########################## APPLICATION INFORMATION ####################################### -->
<div class="rms-overlay" id="additem">
	<div class="rms-dialog-inner" id="mydiv" style="overflow: hidden; min-width:200px; min-height:200px; max-width:900px; z-index:1">
		<div class="rms-modal-title drag" id="mydivheader" style="padding-left:15px;width:950px;cursor:move">
			<span style="float:left"><i class="fa fa-bars"></i></span>
			<span style="text-align:left" id="additem_title">VIEW ORDER REQUEST</span>
			<div class="rms-modal-close" onClick="closeDialoque('additem');"><h4>&times</h4></div>
		</div>
		<div class="rms-modal-content" style="padding:10px 20px 10px 20px ">			
			<div id="additem_page" style="width:100%;position:relative">Loading...</div>
			<div class="notification bg-warning results" id="notification"></div>
		</div>
	</div>
</div>
<div class="rms-overlay" id="updating">
	<div class="rms-dialog-inner" style="overflow: hidden; width:400px; z-index:999">
		<div class="rms-modal-title" style="padding-left:15px">
			<span style="float:left"><i class="fa fa-bars"></i></span>
			<span style="text-align:left" id="createorder_title">UPDATING DATA</span>
			<div class="rms-modal-close" onClick="closeDialoque('updating');"><h4>&times</h4></div>
		</div>
		<div class="rms-modal-content" style="padding:20px">			
			<div id="updating_page"></div>
		</div>
	</div>
</div>
<!-- ########################## APPLICATION UPDATING ####################################### -->
<div class="rms-overlay" id="updateapp">
	<div class="rms-dialog-inner" style="overflow: hidden; min-width:350px; width:350px; max-height:100%">
		<div class="rms-modal-title" style="padding-left:15px">
			<span style="float:left"><i class="fa fa-bars"></i></span>
			<span style="text-align:left" id="updateapp_title">SYSTEM UPDATE</span>
			<div id="closeapp" style="display:none" class="rms-modal-close" onClick="closeDialoque('updateapp');"><h4>&times</h4></div>
		</div>
		<div class="rms-modal-content" style="padding:20px">			
			<div id="updateapp_page">				
			</div>
		</div>
	</div>
</div>
<!-- ########################## FIX UPDATE ####################################### -->
<div class="rms-overlay" id="fixids">
	<div class="rms-dialog-inner" id="mydiv" style="overflow: hidden; min-width:350px; max-height:100%">
		<div class="rms-modal-title drag" id="mydivheader" style="padding-left:15px">
			<span style="float:left"><i class="fa fa-bars"></i></span>
			<span style="text-align:left" id="fixids_title">SYSTEM TOOLS</span>
			<div class="rms-modal-close" onClick="closeDialoque('fixids');"><h4>&times</h4></div>
		</div>
		<div class="rms-modal-content" style="padding:20px">			
			<div id="fixids_page">				
			</div>
		</div>
	</div>
</div>
<!-- ########################## HELP CENTER ####################################### -->
<div class="rms-overlay" id="helpcenter" style="z-index:999999999 !important">
	<div class="rms-dialog-inner" style="overflow: hidden; height:90%">
		<div class="rms-modal-title drag" style="padding-left:15px">
			<span style="float:left"><i class="fa fa-bars"></i></span>
			<span style="text-align:left" id="helpcenter_title">VIDEO MANUAL</span>
			<div class="rms-modal-close" onClick="closeDialoque('helpcenter');"><h4>&times</h4></div>
		</div>
		<div class="rms-modal-content" style="padding:20px">			
			<div id="helpcenter_page"></div>
		</div>
	</div>
</div>
<!-- ########################## OFFLINE MODE ####################################### -->
<div class="rms-overlay" id="offlinemode">
	<div class="rms-dialog-inner" id="mydiv" style="overflow: hidden; min-width:350px; max-height:100%">
		<div class="rms-modal-title drag" id="mydivheader" style="padding-left:15px">
			<span style="float:left"><i class="fa fa-bars"></i></span>
			<span style="text-align:left" id="offlinemode_title">OFFLINE MODE ENCODING</span>
			<div class="rms-modal-close" onClick="closeDialoque('offlinemode');"><h4>&times</h4></div>
		</div>
		<div class="rms-modal-content" style="padding:20px">			
			<div id="offlinemode_page">				
			</div>
		</div>
	</div>
</div>
<!-- ########################## SHIFTING LOGIN ####################################### -->
<div class="rms-overlay" id="loginngadminnapogi">
	<div class="rms-dialog-inner" style="overflow-y: none; min-width:300px;  max-height:85%">
		<div class="rms-modal-title" style="padding-left:15px">
			<span style="float:left"><i class="fa fa-bars"></i></span>
			<span style="text-align:left" id="loginngadminnapogi_title">Administrator Login Only</span>
			<div class="rms-modal-close" onClick="closeDialoque('loginngadminnapogi');"><h4>&times</h4></div>
		</div>
		<div class="rms-modal-content" style="padding:20px">			
			<div id="loginngadminnapogi_page">				
			</div>
		</div>
	</div>
</div>
<script src="../modules/modals/modals.js"></script>