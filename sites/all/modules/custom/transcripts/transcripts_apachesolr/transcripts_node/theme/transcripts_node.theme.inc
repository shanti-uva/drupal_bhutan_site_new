<?php

function theme_transcripts_node_connected_tier_list($vars) {
  return '<strong>Connected language tiers:</strong> ' . implode(', ', array_values($vars['tiers']));
}
