<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Backend\Test\Block\Widget;

use Magento\Mtf\Client\Element\SimpleElement;
use Magento\Ui\Test\Block\Adminhtml\AbstractFormContainers;
use Magento\Mtf\Client\Locator;
use Magento\Mtf\Client\ElementInterface;
use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Mtf\Fixture\InjectableFixture;

/**
 * Is used to represent any form with tabs on the page.
 *
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class FormTabs extends AbstractFormContainers
{
    /**
     * List of tabs.
     *
     * @return array
     */
    public function getTabs()
    {
        return $this->containers;
    }

    /**
     * Get Tab class.
     *
     * @param string $tabName
     * @return Tab
     * @throws \Exception
     */
    public function getTab($tabName)
    {
        return $this->getContainer($tabName);
    }

    /**
     * Fill data into tabs.
     *
     * @param array $tabsData
     * @param SimpleElement $element
     * @return $this
     */
    protected function fillTabs($tabsData, $element)
    {
        return $this->fillContainers($tabsData, $element);
    }

    /**
     * Update form with tabs.
     *
     * @param FixtureInterface $fixture
     * @return FormTabs
     */
    public function update(FixtureInterface $fixture)
    {
        $tabs = $this->getFixtureFieldsByContainers($fixture);
        foreach ($tabs as $tab => $tabFields) {
            $this->openTab($tab)->setFieldsData($tabFields, $this->_rootElement);
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function openContainer($tabName)
    {
        return $this->openTab($tabName);
    }

    /**
     * Open tab.
     *
     * @param string $tabName
     * @return Tab
     * @throws \Exception
     */
    public function openTab($tabName)
    {
        $this->browser->find($this->header)->hover();
        if (! $this->isTabVisible($tabName)) {
            throw new \Exception(
                'Tab "' . $tabName . '" is not visible.'
            );
        }
        $this->getTabElement($tabName)->click();
        return $this;
    }

    /**
     * Get tab element.
     *
     * @param string $tabName
     * @return ElementInterface
     */
    protected function getTabElement($tabName)
    {
        $selector = $this->containers[$tabName]['selector'];
        $strategy = isset($this->containers[$tabName]['strategy'])
            ? $this->containers[$tabName]['strategy']
            : Locator::SELECTOR_CSS;
        return $this->_rootElement->find($selector, $strategy);
    }

    /**
     * Check whether tab is visible.
     *
     * @param string $tabName
     * @return bool
     */
    public function isTabVisible($tabName)
    {
        return $this->getTabElement($tabName)->isVisible();
    }
}
