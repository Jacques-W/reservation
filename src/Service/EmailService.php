<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class EmailService
{

	private $mailer;

	public function __construct(
		MailerInterface $mailer
	) {
		$this->mailer = $mailer;
		$this->mon_email = 'demo.wf3.victor@gmail.com';
		$this->data = array(
			'from' => false,
			'to' => false,
			'replyTo' => false,
			'subjet' => false,
			'template' => false,
			'contexte' => false

		);
	}

	

	public function send($data)
	{

		// Envoyer l'email
		$data = array_merge($this->data, $data);

		if (!$data['from']) {
			$data['from'] = $_ENV['MY_EMAIL'];
		}
		if (!$data['to']) {
			$data['to'] = $_ENV['MY_EMAIL'];
		}
		if (!$data['replyTo']) {
			$data['replyTo'] = $data['from'];
		}

		if ($_ENV['APP_ENV'] == 'dev') {
			$data['to'] = $_ENV['MY_EMAIL'];
		}
		$email = (new TemplatedEmail())
			->from($data['from'])
			->to($data['to'])
			->replyTo($data['replyTo'])
			->subject($data['subject'])
			->htmlTemplate($data['template'])
			->context($data['context']);
		$this->mailer->send($email);
	}
	public function password_forgotten($user, $link)
	{
		$data = array(
			'to' => $user->getEmail(),
			'subject' => "Modifier votre mot de passe",
			'template' => 'emails/security/password_forgotten.email.twig',
			'context' => ['user'=>$user, 'link' => $link]

		);
		$this->send($data);
	}
	public function register($user)
	{
		$data = array(
			'to' => $user->getEmail(),
			'subject' => "Confirmez votre inscription",
			'template' => 'emails/security/register.email.twig',
			'context' => ['user'=>$user]

		);
		$this->send($data);
	}




}
