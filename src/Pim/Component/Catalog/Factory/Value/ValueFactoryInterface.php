<?php
namespace Pim\Component\Catalog\Factory\Value;

use Pim\Component\Catalog\Model\AttributeInterface;
use Pim\Component\Catalog\Model\ValueInterface;

/**
 * Factory that creates product values.
 *
 * @internal  Interface for the factories used internally by \Pim\Component\Catalog\Factory\ValueFactory.
 *
 * @author    Julien Janvier <j.janvier@gmail.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface ValueFactoryInterface
{
    /**
     * This method effectively creates a product value and directly set the data.
     * Channel and locale codes validity MUST HAVE BEEN checked BEFORE.
     *
     * @param AttributeInterface $attribute
     * @param string             $channelCode
     * @param string             $localeCode
     * @param mixed              $data
     *
     * @return ValueInterface
     */
    public function create(AttributeInterface $attribute, $channelCode, $localeCode, $data);

    /**
     * @param string $attributeType
     *
     * @return bool
     */
    public function supports($attributeType);
}
