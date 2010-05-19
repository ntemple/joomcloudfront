<?php defined('_JEXEC') or die('Restricted access'); ?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Details' ); ?></legend>

		<table class="admintable">
		<tr>
      <td width="100" align="right" class="key">
        <label for="path">
          <?php echo JText::_( 'path' ); ?>:
        </label>
      </td>
      <td>
        <input class="text_area" type="text" name="path" id="path" size="32" maxlength="250" value="<?php echo $this->asset->path;?>" />
      </td>            
		</tr>
		<tr>
      <td width="100" align="right" class="key">
        <label for="resource">
          <?php echo JText::_( 'resource' ); ?>:
        </label>
      </td>
      <td>
        <input class="text_area" type="text" name="resource" id="resource" size="32" maxlength="250" value="<?php echo $this->asset->resource;?>" />
      </td>            
		</tr>
		<tr>
      <td width="100" align="right" class="key">
        <label for="md5">
          <?php echo JText::_( 'md5' ); ?>:
        </label>
      </td>
      <td>
        <input class="text_area" type="text" name="md5" id="md5" size="32" maxlength="250" value="<?php echo $this->asset->md5;?>" />
      </td>            
		</tr>
		<tr>
      <td width="100" align="right" class="key">
        <label for="assettype">
          <?php echo JText::_( 'assettype' ); ?>:
        </label>
      </td>
      <td>
        <input class="text_area" type="text" name="assettype" id="assettype" size="32" maxlength="250" value="<?php echo $this->asset->assettype;?>" />
      </td>            
		</tr>
		<tr>
      <td width="100" align="right" class="key">
        <label for="version">
          <?php echo JText::_( 'version' ); ?>:
        </label>
      </td>
      <td>
        <input class="text_area" type="text" name="version" id="version" size="32" maxlength="250" value="<?php echo $this->asset->version;?>" />
      </td>            
		</tr>
		<tr>
      <td width="100" align="right" class="key">
        <label for="distribution_id">
          <?php echo JText::_( 'distribution_id' ); ?>:
        </label>
      </td>
      <td>
        <input class="text_area" type="text" name="distribution_id" id="distribution_id" size="32" maxlength="250" value="<?php echo $this->asset->distribution_id;?>" />
      </td>            
		</tr>
		<tr>
      <td width="100" align="right" class="key">
        <label for="enabled">
          <?php echo JText::_( 'enabled' ); ?>:
        </label>
      </td>
      <td>
        <input class="text_area" type="text" name="enabled" id="enabled" size="32" maxlength="250" value="<?php echo $this->asset->enabled;?>" />
      </td>            
		</tr>
          
	</table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_cloudfront" />
<input type="hidden" name="id" value="<?php echo $this->asset->cloudfront_asset_id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="asset" />
</form>


