<?php

namespace Digitick\Sepa\Tests\Unit\TransferInformation;

use Digitick\Sepa\Exception\InvalidArgumentException;
use Digitick\Sepa\TransferInformation\CustomerDirectDebitTransferInformation;
use PHPUnit\Framework\TestCase;

/**
 * SEPA file generator.
 *
 * @copyright © Blage <www.blage.net> 2015
 * @license GNU Lesser General Public License v3.0
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Lesser Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

class CustomerDirectDebitTransferInformationTest extends TestCase
{
    /**
     * Tests whether the EndToEndId equals the name if no other identifier was supplied
     */
    public function testEndToEndIndentifierEqualsName(): void
    {
        $information = new CustomerDirectDebitTransferInformation(100, 'DE12500105170648489890', 'Their Corp');
        $this->assertEquals('Their Corp', $information->getEndToEndIdentification());
    }

    /**
     * Tests whether the EndToEndId equals the supplied EndToEndId
     */
    public function testOptionalEndToEndIdentifier(): void
    {
        $information = new CustomerDirectDebitTransferInformation(100, 'DE12500105170648489890', 'Their Corp', 'MyEndToEndId');
        $this->assertEquals('MyEndToEndId', $information->getEndToEndIdentification());
    }

    public function testHasAmendmentReturnsTrueForAmendments(): void
    {
        $transferInformation = new CustomerDirectDebitTransferInformation(
            100,
            'DE89370400440532013000',
            'Me'
        );
        $this->assertFalse($transferInformation->hasAmendments());

        $transferInformation->setAmendedDebtorAccount(true);
        $this->assertTrue($transferInformation->hasAmendments());

        $transferInformation->setAmendedDebtorAccount(false);
        $transferInformation->setOriginalDebtorIban('DE89370400440532013000');
        $this->assertTrue($transferInformation->hasAmendments());
    }

    public function testIntAreAccepted(): void
    {
        $transfer = new CustomerDirectDebitTransferInformation(
            19,
            'IbanOfDebitor',
            'DebitorName'
        );

        $this->assertEquals(19, $transfer->getTransferAmount());
    }

    public function testStructuredAddressIsSanitized(): void
    {
        $transfer = new CustomerDirectDebitTransferInformation(100, 'DE12500105170648489890', 'Their Corp');
        $transfer->setStreetName('49 rue de l\'Église');
        $transfer->setPostCode('75008');
        $transfer->setTownName('Saint-Étienne');

        $this->assertEquals('49 rue de l Eglise', $transfer->getStreetName());
        $this->assertEquals('75008', $transfer->getPostCode());
        $this->assertEquals('Saint-Etienne', $transfer->getTownName());
    }

    public function testStructuredAddressIsNullWhenEmpty(): void
    {
        $transfer = new CustomerDirectDebitTransferInformation(100, 'DE12500105170648489890', 'Their Corp');
        $transfer->setStreetName('');
        $transfer->setPostCode(null);
        $transfer->setTownName('');

        $this->assertNull($transfer->getStreetName());
        $this->assertNull($transfer->getPostCode());
        $this->assertNull($transfer->getTownName());
    }
}
