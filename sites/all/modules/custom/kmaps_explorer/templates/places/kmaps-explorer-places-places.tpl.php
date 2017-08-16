<h6>Places Related to <?php print $title; ?> </h6>
<a href="https://places.kmaps.virginia.edu/features/<?php echo $kid; ?>.csv" class="btn btn-sm btn-primary kmaps-btn-down">Download</a>
<div class="places-in-places kmaps-list-columns">
  <?php foreach($data as $aItem => $aItemValue): ?>
    <h6><?php print $title; ?> <?php print $aItem; ?> (<?php print count($aItemValue); ?>):</h6>
    <ul>
      <?php foreach($aItemValue as $bItem => $bItemValue): ?>
        <li>
          <?php print $bItem; ?> (<?php print count($bItemValue); ?>)
          <ul>
            <?php foreach($bItemValue as $cItem => $cItemValue): ?>
              <li>
                <span><?php print $cItemValue->related_places_header_s; ?></span>
                <span class="popover-kmaps" data-app="places" data-id="<?php print explode("-", $cItemValue->related_places_id_s)[1]; ?>">
                  <span class="popover-kmaps-tip"></span>
                  <span class="icon shanticon-menu3"></span>
                </span>
              </li>
            <?php endforeach; ?>
          </ul>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endforeach; ?>
</div>
