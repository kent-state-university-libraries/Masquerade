<?php

class Masquerade_IndexController extends Omeka_Controller_AbstractActionController
{
    public function indexAction()
    {
        $flashMessenger = $this->_helper->FlashMessenger;
        include_once MASQUERADE_DIR . '/forms/form.php';
        try {
            $this->view->form = new Masquerade_IndexForm();
            $this->view->form->create();
        }
        catch(Exception $e) {
            $flashMessenger->addMessage('Error rendering edit form ' . $e, 'error');
        }
    }

    public function saveAction()
    {
        $params = $this->_getAllParams();

        $session = $db->query("SELECT * FROM `{$db->prefix}sessions`
            WHERE id = ?", session_id())->fetchObject();
        $data = explode('}', session_encode());
        $new_data = '';
        foreach ($data as $element) {
            list($object, $val) = explode('|', $element);
            $val .= '}';
            if ($object == 'Zend_Auth') {
                $d = unserialize($val);
                $d['storage'] = (int) $params['account'];
                $val = serialize($d);
            }
            $new_data .= $object . '|' . $val;
        }
        $new_data = trim($new_data, '}');
        session_decode($new_data);

        $this->_helper->redirector->gotoUrl('/');
    }
}
