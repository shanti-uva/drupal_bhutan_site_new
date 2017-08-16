<h6 class="visuals-title"><?php print $title; ?></h6>
<div class="kmaps-visuals-grid">
  <ul class="shanti-gallery">
  <?php foreach($data->docs as $aItem): ?>
    <li class="shanti-thumbnail visual">
      <div class="shanti-thumbnail-image shanti-field-visuals">
        <a href="<?php print base_path() . $app . '/' . $sid . '/visuals/node/' . $aItem->id . '/nojs'; ?>" class="use-ajax shanti-thumbnail-link">
          <span class="overlay"><span class="icon"></span></span>
          <img typeof="foaf:Image" src="<?php print $aItem->url_thumb; ?>" alt="Trumps Acceptance Speech">     
          <span class="icon shanticon-visuals"></span>
        </a>
      </div>
      <!-- End of shanti-thumbnail-image -->
      <div class="shanti-thumbnail-info">
        <div class="body-wrap">
          <div class="shanti-thumbnail-field clearfix shanti-field-title">
            <span class="field-content">
              <a href="<?php print base_path() . $app . '/' . $sid . '/visuals/node/' . $aItem->id . '/nojs'; ?>" class="use-ajax shanti-thumbnail-link">
                <?php print $aItem->caption; ?>
              </a>
            </span>
          </div>
          <div class="shanti-thumbnail-field clearfix shanti-field-agent">
            <span rel="sioc:has_creator">
              Than Grove
            </span>            
          </div>
          <div class="shanti-thumbnail-field clearfix shanti-field-created">
            <span class="shanti-field-content">Nov 14 2016</span>
          </div>
          <div class="shanti-thumbnail-field clearfix shanti-field-type">
            <span class="shanti-field-content">Word Cloud</span>
          </div>
        </div>
        <!-- end body-wrap -->
        <div class="footer-wrap">
          <div class="shanti-field-content">
          </div>
        </div>
        <!-- end footer -->
      </div>
      <!-- end shanti-thumbnail-info -->
    </li>
  <?php endforeach; ?>
  </ul>
</div>