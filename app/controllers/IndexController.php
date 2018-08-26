<?php

class IndexController extends \HXPHP\System\Controller
{
    public function __construct(\HXPHP\System\Configs\Config $configs = null)
    {
        parent::__construct($configs);

        $this->load(
            'Services\Auth',
            $configs->auth->after_login,
            $configs->auth->after_logout,
            true
        );

        $this->load('Storage\Session');

        $blacklist = $this->session->get('blacklist');

        if (!is_null($blacklist)) {
            if (in_array($this->request->server('REMOTE_ADDR'), $blacklist, true)) {
                $this->redirectTo('blank', false, false);
            }
        }
    }

    public function indexAction()
    {
        $this->auth->redirectCheck(true);

        $this->view->setTemplate(false);
    }

    public function acessarAction()
    {
        $this->auth->redirectCheck(true);

        $this->view->setTemplate(false)
            ->setFile('index');

        $this->request->setCustomFilters(array(
            'email' => FILTER_VALIDATE_EMAIL
        ));

        $post = $this->request->post();

        if (!empty($post)) {
            if ($this->captchaSuccess($post['g-recaptcha-response'])) {
                $usuario = Usuario::find_by_email($post['email']);

                if (!empty($usuario)) {
                    if ($usuario->is_ativo == 1) {
                        if (password_verify($post['senha'], $usuario->senha)) {
                            $this->auth->login($usuario->id_pessoa, $usuario->email, $usuario->funcao);
                        } else {
                            $this->load('Modules\Messages', 'auth');

                            $this->messages->setBlock('alerts');

                            $this->load('Helpers\Alert',
                                $this->messages->getByCode('dados-incorretos')
                            );
                        }
                    } else {
                        $this->load('Modules\Messages', 'auth');

                        $this->messages->setBlock('alerts');

                        $this->load('Helpers\Alert',
                            $this->messages->getByCode('usuario-desativado')
                        );
                    }
                } else {
                    $this->load('Modules\Messages', 'auth');

                    $this->messages->setBlock('alerts');

                    $this->load('Helpers\Alert',
                        $this->messages->getByCode('usuario-inexistente')
                    );
                }
            } else {
                $blacklist = array($this->request->server('REMOTE_ADDR'));

                if ($this->session->exists('blacklist')) {
                    $blacklist = array_merge($blacklist, $this->session->get('blacklist'));
                }

                $this->session->set('blacklist', $blacklist);

                $this->redirectTo('blank', false, false);
            }
        }
    }

    public function sairAction()
    {
        $this->auth->logout();
    }

    private function captchaSuccess($response)
    {
        return json_decode(\HXPHP\System\Services\SimplecURL\SimplecURL::connect(
            'https://www.google.com/recaptcha/api/siteverify',
            array(
                'secret' => '6Ldqc2MUAAAAAK-HjGqf-nj0DC99adUy-Ry1SvNJ',
                'response' => $response
            )
        ), true)['success'];
    }
}