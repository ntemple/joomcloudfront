<?php defined('_JEXEC') or die('Restricted access'); ?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Details' ); ?></legend>

		<table class="admintable">
		<tr>
      <td width="100" align="right" class="key">
        <label for="name">
          <?php echo JText::_( 'name' ); ?>:
        </label>
      </td>
      <td>
        <input class="text_area" type="text" name="name" id="name" size="32" maxlength="250" value="<?php echo $this->assettype->name;?>" />
      </td>            
		</tr>
		<tr>
      <td width="100" align="right" class="key">
        <label for="extension">
          <?php echo JText::_( 'extension' ); ?>:
        </label>
      </td>
      <td>
        <input class="text_area" type="text" name="extension" id="extension" size="32" maxlength="250" value="<?php echo $this->assettype->extension;?>" />
      </td>            
		</tr>
		<tr>
      <td width="100" align="right" class="key">
        <label for="mime">
          <?php echo JText::_( 'mime' ); ?>:
        </label>
      </td>
      <td>
        <input class="text_area" type="text" name="mime" id="mime" size="32" maxlength="250" value="<?php echo $this->assettype->mime;?>" />
      </td>            
		</tr>
		<tr>
      <td width="100" align="right" class="key">
        <label for="gzencode">
          <?php echo JText::_( 'gzencode' ); ?>:
        </label>
      </td>
      <td>
        <input class="text_area" type="text" name="gzencode" id="gzencode" size="32" maxlength="250" value="<?php echo $this->assettype->gzencode;?>" />
      </td>            
		</tr>
		<tr>
      <td width="100" align="right" class="key">
        <label for="enabled">
          <?php echo JText::_( 'enabled' ); ?>:
        </label>
      </td>
      <td>
        <input class="text_area" type="text" name="enabled" id="enabled" size="32" maxlength="250" value="<?php echo $this->assettype->enabled;?>" />
      </td>            
		</tr>
          
	</table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_cloudfront" />
<input type="hidden" name="id" value="<?php echo $this->assettype->cloudfront_assettype_id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="assettype" />
</form>


