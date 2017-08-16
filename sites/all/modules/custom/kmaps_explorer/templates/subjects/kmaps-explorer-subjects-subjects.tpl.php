<h6><?php print 'Subjects Related to ' . $title; ?> </h6>
<a href="https://subjects.kmaps.virginia.edu/features/<?php echo $kid; ?>.csv" class="btn btn-sm btn-primary kmaps-btn-down">Download</a>
<div class="subjects-in-subjects kmaps-list-columns">
  <?php foreach($data as $aItem => $aItemValue): ?>
    <h6><?php print $title; ?> <?php print $aItem; ?> (<?php print count($aItemValue); ?>):</h6>
    <ul>
      <?php foreach($aItemValue as $bItem => $bItemValue): ?>
        <li>
          <span><?php print $bItemValue->related_subjects_header_s; ?></span>
          <span class="popover-kmaps" data-app="subjects" data-id="<?php print $bItemValue->related_subjects_id_s; ?>">
            <span class="popover-kmaps-tip"></span>
            <span class="icon shanticon-menu3"></span>
          </span>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endforeach; ?>
</div>
