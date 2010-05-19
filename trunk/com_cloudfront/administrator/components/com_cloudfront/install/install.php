<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<h1>Cloudfront for Joomla!</h1>
<script>$$('table.adminform')[0].getElementsByTagName('tr')[0].setStyle('display', 'none');</script>
<table class="adminlist">
	<thead>
		<tr>
			<th class="title"><?php echo JText::_('Task'); ?></th>
			<th width="60%"><?php echo JText::_('Status'); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
	</tfoot>
	<tbody>
		<tr class="row1">
			<td class="key"><?php echo JText::_('PHP Version'); ?></td>
			<td>
				<?php echo version_compare(phpversion(), '5.2', '>=')
					? '<strong>'.JText::_('OK').'</strong> - '.phpversion()
					: '<em>'.JText::_('You need at least PHP v5.2 to use Cloudfront. You are using: ').phpversion().'</em>'; ?>
			</td>
		</tr>
		<tr class="row0">
			<td class="key"><?php echo JText::_('MySQL Version'); ?></td>
			<td>
				<?php echo version_compare($db->getVersion(), '5.0.41', '>=')
				? '<strong>'.JText::_('OK').'</strong> - '.$db->getVersion()
				: '<em>'.JText::_('You need at least MySQL v5.0.41 to use Cloudfront. You are using: ').$db->getVersion().'</em>'; ?>
			</td>
		</tr>
	</tbody>
</table>

