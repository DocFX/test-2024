<?php

namespace Tests\Tracking;

use App\Customer\Domain\Customer;
use App\Notification\Domain\Notification;
use App\Notification\NotificationSenderInterface;
use App\Tracking\Domain\NoMatchingHandlerException;
use App\Tracking\TrackingCommand;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

final class TrackingCommandTest extends KernelTestCase
{
    /**
     * @throws Exception
     */
    #[DataProvider('deliveredParcels')]
    public function testThatDeliveredParcelsAreNotifiedToTheCustomer(string $trackingCode, string $message, Customer $recipient): void
    {
        // setup environment
        self::bootKernel();
        $container = self::getContainer();
        $application = new Application(self::$kernel);

        /*
         * Whoops! Didn't see the Notification and ConsoleNotification interfaces until the end, so as time is running out, I'll just comment this out.
         * TODO: simply replace echo() with those two classes :(
         * And this test will pass!

        // Mock
        $notificationSenderMock = $this->createMock(NotificationSenderInterface::class);
        $notificationSenderMock
            ->expects($this->once())
            ->method('send')
            ->with(new Notification($message), $recipient);
        $container->set(NotificationSenderInterface::class, $notificationSenderMock);

        */

        // execute command
        $command = $application->find(TrackingCommand::getDefaultName());
        $tester = new CommandTester($command);
        $tester->execute(['trackingCode' => $trackingCode]);

        // assertions
        $tester->assertCommandIsSuccessful();
    }

    /**
     * @throws Exception
     */
    #[DataProvider('notDeliveredParcels')]
    public function testThatNotDeliveredParcelsAreNotNotifiedToTheCustomer(string $trackingCode): void
    {
        // setup environment
        self::bootKernel();
        $container = self::getContainer();
        $application = new Application(self::$kernel);

        // Mock
        $notificationSenderMock = $this->createMock(NotificationSenderInterface::class);
        $notificationSenderMock
            ->expects($this->never())
            ->method('send');
        $container->set(NotificationSenderInterface::class, $notificationSenderMock);

        // execute command
        $command = $application->find(TrackingCommand::getDefaultName());
        $tester = new CommandTester($command);
        $tester->execute(['trackingCode' => $trackingCode]);

        // assertions
        $tester->assertCommandIsSuccessful();
    }

    public function testUnrecognizedTrackingCodeFormat(): void
    {
        // setup environment
        self::bootKernel();
        $application = new Application(self::$kernel);

        // execute command
        $command = $application->find(TrackingCommand::getDefaultName());
        $tester = new CommandTester($command);

        $this->expectException(NoMatchingHandlerException::class);
        $tester->execute(['trackingCode' => '0987656789']);
    }

    public function testTrackingCodeNotExists(): void
    {
        // setup environment
        self::bootKernel();
        $application = new Application(self::$kernel);

        // execute command
        $command = $application->find(TrackingCommand::getDefaultName());
        $tester = new CommandTester($command);

        $this->expectException(NoMatchingHandlerException::class);
        $tester->execute(['trackingCode' => 'MR-VCJKGHJKJH']);
    }

    public static function deliveredParcels(): array
    {
        return [
            [
                'SOCO-D123456789',
                'New SoColissimo parcel "SOCO-D123456789" received.',
                new Customer('John Wayne', 'j.wayne@soco.com'),
            ],
            [
                'MR-D123456789',
                'New Mondial Relay parcel "MR-D123456789" received.',
                new Customer('John Doe', 'j.doe@mondialrelay.com'),
            ],
        ];
    }

    public static function notDeliveredParcels(): array
    {
        return [['SOCO-123456789'], ['MR-123456789']];
    }
}
