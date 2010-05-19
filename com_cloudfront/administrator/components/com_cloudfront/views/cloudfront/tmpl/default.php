<?php defined('_JEXEC') or die('Restricted access'); ?>

<script language="javascript">
function confirmation() {
  var answer = confirm('Scanning can take many minutes and may timeout on slow servers - please be patient.' + "\n" + ' Click "Ok" to continue or "Cancel" to abort.')
  if (answer) {
    window.location = 'index.php?option=com_cloudfront&view=cloudfront&task=scan';
  } 
}
</script>

<h1>Cloudfront Dashboard</h1>
<div class="col50">
  <fieldset class="adminform">
    <legend>Setup</legend>
    <table class="adminlist">
      <tr class="row0">
        <td>
<h2>Setting Up</h2>
<ol>
<li>Select "Manage Plugin" to configure your S3 / Cloudfront credentials.</li>
<li>Select "Distributions" to setup your Cloudfront paths</li>
<li>Look below to upload all your static files to S3.</li>
<li>Use "Manage Plugin" to gather your data</li>
<li>Finally, set runmode to "Cloudfront"</li>
</ll>        </td>
      </tr>
    </table>
  </fieldset>
</div>
<div class="col50">
  <fieldset class="adminform">
    <legend>Scan and Upload</legend>
    <table class="adminlist">
      <tr class="row0">
        <td>
<p>The system will now scan your file system, looking for static content and uploading the static files
to S3 for use with CloudFront.  You will want to re-scan whenever your files have changed, or you have
added new ones. The system is efficient, and will only upload files that are new or have changed since
your last scan.</p>
<p>Plase be aware that this operation can take a <b>very long time</b>, ten minutes or longer in some cases.</p>
<p>Press the button below to begin.</p>
<hr>
<input type="button" onclick="confirmation()" value="Scan and Upload"> 

        </td>
      </tr>
    </table>
  </fieldset>
</div>







