<?php

/**
 * @creator - Harish Kumar B P from GlyphWebCore Technologies
 * @website - http://www.magedevelopment.com
 * @module  - GlyphWebCore NewsletterTest
 *
**/

class GlyphWebCore_NewsletterTest_Adminhtml_SendtestController extends Mage_Adminhtml_Controller_Action
{	



    public function indexAction()
    {
		$this->loadLayout();
        $this->renderLayout();
    }
    
    
    

    public function postAction()
    {
        $post = $this->getRequest()->getPost();
		$sql = "SELECT * FROM newsletter_queue WHERE queue_id =".$post['id'];
		$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
				
		foreach($connection->fetchAll($sql) as $myrow) 
		{
 			$sender = $myrow['newsletter_sender_email'];		
		 	$subject = $myrow['newsletter_subject'];
 			$message = $myrow['newsletter_text'];
		}
        
        try 
        {
        	if(empty($post))
        	{
                Mage::throwException($this->__('Invalid form data.'));
            }
            
            $mail = new Zend_Mail();
            $mail->setFrom($sender);
            $mail->setBodyHtml($message);
            $mail->addTo($post['email'], $post['name']);
            $mail->setSubject($subject);
            $mail->send();            
            
            $message = $this->__("Newsletter sent to ".$post['email'].". Please check both your Inbox and Spam folders for the Newsletter Test Email.");
            Mage::getSingleton('adminhtml/session')->addSuccess($message);
        } 
        catch (Exception $e) 
        {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }      
        
        $this->_redirect('*/*/');
    }
    	
}