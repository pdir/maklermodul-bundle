<?php

declare(strict_types=1);

namespace Pdir\MaklermodulBundle\Migration;

use Contao\Automator;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Contao\Folder;
use Contao\System;
use Doctrine\DBAL\Exception;

class InitialFilesFolderMigration extends AbstractMigration
{
    /**
     * module folder.
     */
    const strFolder = 'maklermodul/';

    /**
     * Data folder.
     */
    public static string $strDataFolder = self::strFolder.'data';

    /**
     * Import folder.
     */
    public static string $strImportFolder = self::strFolder.'upload';

    /**
     * Backup folder.
     */
    public static string $strOrgFolder = self::strFolder.'org';

    public function __construct(ContaoFramework $contaoFramework,)
    {
    }

    public function getName(): string
    {
        return 'Initial files folder migration - Maklermodul Bundle';
    }

    /**
     * @throws Exception
     */
    public function shouldRun(): bool
    {
        $this->contaoFramework->initialize();

        $this->uploadPath = System::getContainer()->getParameter('contao.upload_path');
        #$this->projectDir = System::getContainer()->getParameter('kernel.project_dir');

        // If the folder exists we should do nothing
        if (file_exists($this->uploadPath.'/'.self::$strDataFolder)) {
            return false;
        }

        return true;
    }

    /**
     * @throws \Exception
     */
    public function run(): MigrationResult
    {
        // Create folders
        $folder = new Folder($this->uploadPath.'/files/'.self::$strDataFolder);
        $folder = new Folder($this->uploadPath.'/files/'.self::$strImportFolder);
        $folder = new Folder($this->uploadPath.'/files/'.self::$strOrgFolder);

        // generate symlinks
        (new Automator())->generateSymlinks();

        return $this->createResult(true, 'Initial bundle folders created.');
    }
}
