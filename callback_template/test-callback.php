<?php

// dump out results on page
echo '<pre>';
echo '==========USER INFO==========';

print_r($userInfo);
echo '==========PAGE INFO==========';
print_r($pageInfo);
echo "<button id='testButton'>Save</button>";

echo __DIR__;

/* PHP SDK v5.0.0 */
/* make the API call */
try {
    $response = $fb->get(
        '[page-id]/insights/page_fans',
        '[page-access-token]'
        );
} catch(Facebook\Exceptions\FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}
$getGraphEdge = $response->getGraphEdge();
/* handle the result */
print_r($getGraphEdge);