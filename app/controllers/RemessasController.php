<?php

class RemessasController extends \HXPHP\System\Controller
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

        $this->auth->redirectCheck(false);

        $this->auth->roleCheck(array('C', 'O'));
    }

    public function indexAction($id = null)
    {
        $this->view->setPath('blank', true)
            ->setFile('index')
            ->setTemplate(false);

        if (!empty(filter_var($id, FILTER_VALIDATE_INT))) {
            $remessa = Remessa::find_by_id($id);

            if (!empty($remessa)) {
                $remetente = Pessoa::find_by_id($remessa->id_remetente);

                $destinatario = Pessoa::find_by_id($remessa->id_destinatario);

                $diligencias = Diligencia::getAllByRemessa($remessa->id);

                $data = $remessa->data->format('d/m/Y H:i:s');

                $remessa = "<div>" .
                    "<p><h5>Número da remessa</h5>$remessa->id</p>" .
                    "<p><h5>Data da remessa</h5>$data</p>" .
                    "<p><h5>Remetente</h5>$remetente->nome</p>" .
                    "<p><h5>Destinatário</h5>$destinatario->nome</p>" .
                    "<h5>Diligências</h5>" .
                    "<table>" .
                    "<thead>" .
                    "<tr>" .
                    "<th>Número de protocolo</th>" .
                    "<th>Descrição</th>" .
                    "<th>Interessado</th>" .
                    "</tr>" .
                    "</thead>" .
                    "<tbody>";

                foreach ($diligencias as $diligencia) {
                    $mandado = Mandado::find_by_id($diligencia->id_mandado);

                    $interessado = Pessoa::find_by_id($mandado->id_interessado);

                    $remessa .= "<tr>" .
                        "<td>$mandado->numero_protocolo</td>" .
                        "<td>$mandado->descricao</td>" .
                        "<td>$interessado->nome</td>" .
                        "</tr>";
                }

                $remessa .= "</tbody>" .
                    "</table>" .
                    "<div>" .
                    "<p>Remetente: ______________________________________ Data: __/__/____</p>" .
                    "<p>Destinatário: ______________________________________ Data: __/__/____</p>" .
                    "</div>" .
                    "</div>";

                $this->load('Services\Pdf', 'A4');

                $this->pdf->byHtml($remessa);
            }
        }
    }
}