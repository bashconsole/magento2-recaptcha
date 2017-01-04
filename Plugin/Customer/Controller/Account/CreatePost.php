<?php
namespace Bashconsole\ReCaptcha\Plugin\Customer\Controller\Account;

class CreatePost
{
    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    protected $resultRedirectFactory;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @var \Bashconsole\ReCaptcha\Helper\Data $dataHelper
     */
    protected $dataHelper;

    /**
     * @param \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Bashconsole\ReCaptcha\Helper\Data $dataHelper
     */
    public function __construct(
        \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Bashconsole\ReCaptcha\Helper\Data $dataHelper
    ) {
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->messageManager = $messageManager;
        $this->dataHelper = $dataHelper;
    }

    public function aroundExecute(
        \Magento\Customer\Controller\Account\CreatePost $interceptor,
        \Closure $proceed
    ) {
        if($this->dataHelper->isEnabled()) {
            $recaptcha_response_field = $interceptor->getRequest()->getPost('g-recaptcha-response');
            if($recaptcha_response_field) {
                $secretKey = $this->dataHelper->getSecretKey();
                $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$recaptcha_response_field."&remoteip=".$_SERVER['REMOTE_ADDR']);
                $result = json_decode($response, true);
                if(isset($result['success']) && ($result['success'])) {
                    return $proceed();
                } else {
                    $resultRedirect = $this->resultRedirectFactory->create();
                    $this->messageManager->addError(
                        __('There was an error with the recaptcha code, please try again.')
                    );
                    $resultRedirect->setPath('customer/account/create');
                    return $resultRedirect;
                }
            } else {
                $this->messageManager->addError(
                    __('There was an error with the recaptcha code, please try again.')
                );
                $resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->setPath('customer/account/create');
                return $resultRedirect;
            }
        }

        return $proceed();
    }
}