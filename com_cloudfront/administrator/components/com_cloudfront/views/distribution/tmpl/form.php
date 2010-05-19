<?php defined('_JEXEC') or die('Restricted access'); ?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Details' ); ?></legend>

		<table class="admintable">
		<tr>
      <td width="100" align="right" class="key">
        <label for="host">
          <?php echo JText::_( 'host' ); ?>:
        </label>
      </td>
      <td>
        <input class="text_area" type="text" name="host" id="host" size="32" maxlength="250" value="<?php echo $this->distribution->host;?>" />
      </td>            
		</tr>
		<tr>
      <td width="100" align="right" class="key">
        <label for="enabled">
          <?php echo JText::_( 'enabled' ); ?>:
        </label>
      </td>
<!--
      <td>
        <input class="text_area" type="text" name="enabled" id="enabled" size="32" maxlength="250" value="<?php echo $this->distribution->enabled;?>" />
      </td>       
-->     
		</tr>
          
	</table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_cloudfront" />
<input type="hidden" name="id" value="<?php echo $this->distribution->cloudfront_distribution_id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="distribution" />
</form>


