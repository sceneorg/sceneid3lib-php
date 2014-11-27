<?php

/////////////////////////////////////////////////
// Preamble
//
// To get a clientID, visit https://id.scene.org/docs/

include_once("sceneid3.inc.php");

$sceneID = new SceneID3( array(
  "clientID"     => "myPortalClientID",
  "clientSecret" => "verySecretHashThing",
  "redirectURI"  => "http://my.domain.tld/return.url",
) );

/////////////////////////////////////////////////
// Act 1 - Operations that don't require login
//
// First, we get a client-token.

try
{
  // this is optional to call explicitly
  // resource calls will automatically call this
  // if there's no access token claimed yet.
  $sceneID->GetClientCredentialsToken();

  // We got it, now we can query at will
  $result = $sceneID->User(1);
  print_r($result);
}
catch( SceneID3AuthException $e )
{
  // something went wrong
}

/////////////////////////////////////////////////
// if we want to invalidate the previous token
// we can call Reset()
$sceneID->Reset();

/////////////////////////////////////////////////
// Act 2 - Operations that do require a user
//
// This is a bit trickier
// First, we redirect to the SceneID login page

$sceneID->PerformAuthRedirect();

// After that, on the redirect page, we process
// the response sent by SceneID

try
{
  $sceneID->ProcessAuthResponse();
  // Note: this only needs to be done once, the tokens are stored in sessions

  // We can now identify the user by their login
  $result = $sceneID->Me();
  print_r($result);
}
catch (Exception $e)
{
  // something went wrong
}

?>