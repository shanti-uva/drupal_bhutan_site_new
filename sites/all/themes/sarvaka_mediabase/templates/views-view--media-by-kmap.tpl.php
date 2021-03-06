<?php

/**
 * @file
 * Main view template.
 *
 * Variables available:
 * - $classes_array: An array of classes determined in
 *   template_preprocess_views_view(). Default classes are:
 *     .view
 *     .view-[css_name]
 *     .view-id-[view_name]
 *     .view-display-id-[display_name]
 *     .view-dom-id-[dom_id]
 * - $classes: A string version of $classes_array for use in the class attribute
 * - $css_name: A css-safe version of the view name.
 * - $css_class: The user-specified classes names, if any
 * - $header: The view header
 * - $footer: The view footer
 * - $rows: The results of the view query, if any
 * - $empty: The empty text to display if the view is empty
 * - $pager: The pager next/prev links to display, if any
 * - $exposed: Exposed widget form/info to display
 * - $feed_icon: Feed icon to display, if any
 * - $more: A link to view more, if any
 *
 * @ingroup views_templates
 */
?>
<div class="shanti-view-dom-id" <?php if(isset($variables['dom_id'])) print 'data-dom-id="' . $variables['dom_id'] . '"'; ?>>

	<?php //dpm($variables, 'vars'); ?>
  <?php print render($title_prefix); ?>
  <?php if ($title): ?>
    <h2><?php print $title; ?></h2>
  <?php endif; ?>
  <?php print render($title_suffix); ?>
  <?php if (isset($lineage)) { print $lineage; } ?>
  <?php if ($rows && $pager): ?>
	<?php // record dom-id for reloading by BEF so that it replaces the whole div ?>
			<div class="shanti-filters">
		  	<table>
				    <tr>
				    		<td>
				    			<?php if ($header) { print $header; }?>
				    		</td>
				        <td>
				        	<?php if ($exposed): ?>
			              <span class="view-filters-mb">
			                <?php print $exposed; ?>
			              </span>
			            <?php endif;?>
				        </td>
				        <td>
				            <?php print $pager; ?>
				        </td>
				    </tr>
				</table>
		</div>
	<?php endif; ?>

  <?php if ($attachment_before): ?>
    <div class="attachment attachment-before">
      <?php print $attachment_before; ?>
    </div>
  <?php endif; ?>
	
	<div class="<?php print $classes; ?>">
	  <?php if ($rows): ?>
		    <div class="view-content">
			    <ul class="shanti-gallery">
						<?php print $rows; //dpm($rows, 'rows')?>
					</ul>
		    </div>
	
	  <?php elseif ($empty): ?>
	    <div class="view-empty">
	      <?php print $empty; ?>
	    </div>
	  <?php endif; ?>
	</div><?php /* class view */ ?>
	
  <?php if ($attachment_after): ?>
    <div class="attachment attachment-after">
      <?php print $attachment_after; ?>
    </div>
  <?php endif; ?>

  <?php if ($more): ?>
    <?php print $more; ?>
  <?php endif; ?>

  <?php if ($footer): ?>
    <div class="view-footer">
      <?php print $footer; ?>
    </div>
  <?php endif; ?>

  <?php if ($feed_icon): ?>
    <div class="feed-icon">
      <?php print $feed_icon; ?>
    </div>
  <?php endif; ?>

	<?php if ($rows && $pager): ?>
		<div class="shanti-filters bottom">
			<?php print $pager; ?>
		</div>
	<?php endif; ?>
</div><?php /* end template div*/?>