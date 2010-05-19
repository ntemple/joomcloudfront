<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="index.php" method="post" name="adminForm">
<div id="editcell">
  <table class="adminlist">
  <tfoot>
  <tr>
    <td colspan="2")>
          <?php echo $this->pagination->getListFooter(); ?>
    </td>
  </tr>
  </tfoot>
  <thead>
    <tr>
      <th width="20">
        <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
      </th>      
      <th>
        <?php echo JText::_( 'host' ); ?>
      </th>
<?php /*      
      <th>
        <?php echo JText::_( 'enabled' ); ?>
      </th>
*/ ?>      
    </tr>
  </thead>  
  <tbody>
  <?php
  $k = 0;
  for ($i=0, $n=count( $this->items ); $i < $n; $i++)  {
    $row = &$this->items[$i];
    $checked   = JHTML::_('grid.id',   $i, $row->id );
    $link     = JRoute::_( 'index.php?option=com_cloudfront&controller=distribution&task=edit&cid[]='. $row->cloudfront_distribution_id );
    ?>
    <tr class="<?php echo "row$k"; ?>">    
      <td>
        <?php echo $checked; ?>
      </td>
      <td>
        <a href="<?php echo $link; ?>"><?php echo $row->host; ?></a>
      </td>
<?php /*      
      <td>
        <?php echo $row->enabled; ?>
      </td>
*/ ?>      
      
    </tr>
    <?php
    $k = 1 - $k;
  }
  ?>
  </tbody>
  </table>
</div>

<input type="hidden" name="option" value="com_cloudfront" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="distribution" />
</form>