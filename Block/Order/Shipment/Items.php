<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Sales order view items block
 *
 * @author     Magento Core Team <core@magentocommerce.com>
 */
namespace Dealer4Dealer\SubstituteOrders\Block\Order\Shipment;

class Items extends \Magento\Sales\Block\Items\AbstractItems
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    protected $_orderRepository;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->_orderRepository = $orderRepository;
        parent::__construct($context, $data);
    }

    /**
     * @param Magento order id $id
     * @return order
     */
    public function getOrderById($id) {
        return $this->_orderRepository->get($id);
    }

    public function getShipment() {
        return $this->_coreRegistry->registry('current_shipment');
    }

    /**
     * Retrieve current order model instance
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        return $this->_coreRegistry->registry('current_order');
    }

    /**
     * @param object $shipment
     * @return string
     */
    public function getPrintShipmentUrl($shipment)
    {
        return $this->getUrl('*/*/printShipment', ['id' => $shipment->getId()]);
    }

    /**
     * @param object $order
     * @return string
     */
    public function getPrintAllShipmentsUrl($order)
    {
        return $this->getUrl('*/*/printShipment', ['id' => $order->getId()]);
    }

    /**
     * @param object $order
     * @return string
     */
    public function getShipmentTrackUrl($order)
    {
        return $this->getUrl('*/*/track', ['id' => $order->getId()]);
    }

    /**
     * Get html of shipment comments block
     *
     * @param   \Magento\Sales\Model\Order\Shipment $shipment
     * @return  string
     */
    public function getCommentsHtml($shipment)
    {
        $html = '';
        $comments = $this->getChildBlock('shipment_comments');
        if ($comments) {
            $comments->setEntity($shipment)->setTitle(__('About Your Shipment'));
            $html = $comments->toHtml();
        }
        return $html;
    }

    /**
     * Get html of shipment comments block
     *
     * @param   \Magento\Sales\Model\Order\Shipment $shipment
     * @return  string
     */
    public function getShipmenteAttachmentHtml($shipment)
    {
        $html = '';
        $comments = $this->getChildBlock('shipment_attachments');
        if ($comments) {
            $comments->setEntity($shipment)->setTitle(__('Shipment Attachments'));
            $html = $comments->toHtml();
        }
        return $html;
    }

    public function hasTracking()
    {
        foreach ($this->getOrder()->getShipmentCollection() as $shipment) {
            foreach ($shipment->getTracking() as $tracking) {
                return true;
            }
        }

        return false;
    }
}
