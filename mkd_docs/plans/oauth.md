OAuth Plan
----------
- MemberLogin controller 
- refactor current oauth login controller and callback controller.
  we need a better redirection page and access token callback handler when request is successful 
- Oauth exception page, can setup exception handle controller in config file.
- Setup auth token store with columns:
    - provider: like twitter, facebook .. etc
    - app_id: consumer id or client id
    - identity: user id or screen name, account name ... etc
    - data:  contains   oauth_token or ... any private data.

- Provide auth token store application interface

        $credential = OAuth::findCredential(  'provider_name' , 'app_id' );
        if( ! $credential ) {
            $credential = OAuth::createCredential( 'provider_name' , 'app_id' , array(
            
            ));
        }

- support controller forward.

    write controlelr create synopsis

    $this->forward( 'class name', $args );

- Use setup success controller from Member plugin

    OAuth::setSuccessForward( 'OAuthLoginSuccessController' );
    OAuth::setExceptionForward( 'OAuthExceptionController' );

