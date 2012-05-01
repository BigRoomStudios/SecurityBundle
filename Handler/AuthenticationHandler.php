<?php

namespace BRS\SecurityBundle\Handler;

// "use" statements here
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface; 
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface; 
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface; 
use Symfony\Component\Security\Core\SecurityContext; 
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\HttpFoundation\RedirectResponse; 
use Symfony\Component\Routing\Router; 

class AuthenticationHandler
implements AuthenticationSuccessHandlerInterface,
		   AuthenticationFailureHandlerInterface
{
	private $router;

	public function __construct(Router $router)
	{
		$this->router = $router;
	}

	public function onAuthenticationSuccess(Request $request, TokenInterface $token)
	{
		if ($targetPath = $request->getSession()->get('_security.target_path')) {
			
			$url = $targetPath;
			
		} else {
			
			// Otherwise, redirect him to wherever you want
			$url = $this->router->generate('brs_member_memberadmin_index');
		}	
			
		if ($request->isXmlHttpRequest()) {
			
			$values = array(
				'success' => true,
				'url' => $url,
			);
			
			return new Response(json_encode($values));
			
		} else {
			
			// If the user tried to access a protected resource and was forced to login
			// redirect him back to that resource
		   
			return new RedirectResponse($url);
		}
	}

	public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
	{
			
		//die('here');
			
		$error = $exception->getMessage();
		
		if ($request->isXmlHttpRequest()) {
			
			$values = array(
				'success' => false,
				'error' => $error,
			);
			
			return new Response(json_encode($values));
			
		} else {
			// Create a flash message with the authentication error message
			$request->getSession()->setFlash('error', $error);
			
			$url = $this->router->generate('login');

			return new RedirectResponse($url);
		}
	}
}