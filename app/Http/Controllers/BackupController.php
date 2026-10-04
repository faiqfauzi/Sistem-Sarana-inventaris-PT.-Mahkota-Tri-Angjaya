<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class BackupController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HALAMAN BACKUP & RESTORE
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view('backups.index');
    }


    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD BACKUP DATABASE
    |--------------------------------------------------------------------------
    */

    public function download()
    {
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');

        $mysqldump = env(
            'MYSQL_DUMP_PATH',
            'C:/xampp/mysql/bin/mysqldump.exe'
        );


        /*
        |--------------------------------------------------------------------------
        | NAMA FILE BACKUP
        |--------------------------------------------------------------------------
        */

        $filename = 'backup-inventaris-' .
            now()->format('Y-m-d-H-i-s') .
            '.sql';


        $backupDirectory = storage_path('app/backups');

        if (!is_dir($backupDirectory)) {

            mkdir(
                $backupDirectory,
                0755,
                true
            );
        }


        $backupPath = $backupDirectory .
            DIRECTORY_SEPARATOR .
            $filename;


        /*
        |--------------------------------------------------------------------------
        | BUAT BACKUP
        |--------------------------------------------------------------------------
        */

        $result = $this->createBackup(
            $mysqldump,
            $database,
            $username,
            $password,
            $backupPath
        );


        /*
        |--------------------------------------------------------------------------
        | JIKA GAGAL
        |--------------------------------------------------------------------------
        */

        if (!$result['success']) {

            return back()->with(
                'error',
                'Backup database gagal dibuat: ' .
                    $result['error']
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DOWNLOAD
        |--------------------------------------------------------------------------
        */

        return response()
            ->download(
                $backupPath,
                $filename
            )
            ->deleteFileAfterSend(true);
    }


    /*
    |--------------------------------------------------------------------------
    | RESTORE DATABASE
    |--------------------------------------------------------------------------
    */

    public function restore(Request $request)
    {
        $request->validate([

            'backup_file' => [
                'required',
                'file',
                'extensions:sql',
                'max:51200',
            ],

            'confirmation' => [
                'accepted',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | KONFIGURASI DATABASE
        |--------------------------------------------------------------------------
        */

        $database = config(
            'database.connections.mysql.database'
        );

        $username = config(
            'database.connections.mysql.username'
        );

        $password = config(
            'database.connections.mysql.password'
        );


        $mysql = env(
            'MYSQL_PATH',
            'C:/xampp/mysql/bin/mysql.exe'
        );


        $mysqldump = env(
            'MYSQL_DUMP_PATH',
            'C:/xampp/mysql/bin/mysqldump.exe'
        );


        /*
        |--------------------------------------------------------------------------
        | SIMPAN FILE RESTORE SEMENTARA
        |--------------------------------------------------------------------------
        */

        $restoreDirectory = storage_path(
            'app/restore-temp'
        );


        if (!is_dir($restoreDirectory)) {

            mkdir(
                $restoreDirectory,
                0755,
                true
            );
        }


        $restoreFilename = 'restore-' .
            now()->format('YmdHis') .
            '.sql';


        $request->file('backup_file')->move(
            $restoreDirectory,
            $restoreFilename
        );


        $restorePath = $restoreDirectory .
            DIRECTORY_SEPARATOR .
            $restoreFilename;


        /*
        |--------------------------------------------------------------------------
        | BUAT EMERGENCY BACKUP
        |--------------------------------------------------------------------------
        */

        $backupDirectory = storage_path('app/backups');


        if (!is_dir($backupDirectory)) {

            mkdir(
                $backupDirectory,
                0755,
                true
            );
        }


        $emergencyFilename =
            'emergency-before-restore-' .
            now()->format('Y-m-d-H-i-s') .
            '.sql';


        $emergencyPath = $backupDirectory .
            DIRECTORY_SEPARATOR .
            $emergencyFilename;


        $emergencyBackup = $this->createBackup(
            $mysqldump,
            $database,
            $username,
            $password,
            $emergencyPath
        );


        /*
        |--------------------------------------------------------------------------
        | JIKA EMERGENCY BACKUP GAGAL
        |--------------------------------------------------------------------------
        */

        if (!$emergencyBackup['success']) {

            if (file_exists($restorePath)) {

                unlink($restorePath);
            }


            return back()->with(
                'error',
                'Restore dibatalkan karena emergency backup gagal dibuat: ' .
                    $emergencyBackup['error']
            );
        }


        /*
        |--------------------------------------------------------------------------
        | COMMAND RESTORE MYSQL
        |--------------------------------------------------------------------------
        */

        $command = [

            $mysql,

            '--host=127.0.0.1',

            '--port=3306',

            '--user=' . $username,

        ];


        /*
        |--------------------------------------------------------------------------
        | PASSWORD
        |--------------------------------------------------------------------------
        */

        if (!empty($password)) {

            $command[] = '--password=' . $password;
        }


        /*
        |--------------------------------------------------------------------------
        | DATABASE
        |--------------------------------------------------------------------------
        */

        $command[] = $database;


        /*
        |--------------------------------------------------------------------------
        | JALANKAN MYSQL
        |--------------------------------------------------------------------------
        */

        $process = new Process(
            $command,
            null,
            $this->getWindowsEnvironment()
        );


        /*
        |--------------------------------------------------------------------------
        | KIRIM ISI FILE SQL
        |--------------------------------------------------------------------------
        */

        $process->setInput(
            file_get_contents($restorePath)
        );


        $process->setTimeout(300);

        $process->run();


        /*
        |--------------------------------------------------------------------------
        | HAPUS FILE SEMENTARA
        |--------------------------------------------------------------------------
        */

        if (file_exists($restorePath)) {

            unlink($restorePath);
        }


        /*
        |--------------------------------------------------------------------------
        | JIKA RESTORE GAGAL
        |--------------------------------------------------------------------------
        */

        if (!$process->isSuccessful()) {

            return back()->with(
                'error',
                'Restore database gagal: ' .
                    $process->getErrorOutput()
            );
        }


        /*
        |--------------------------------------------------------------------------
        | BERHASIL
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('backups.index')
            ->with(
                'success',
                'Database berhasil dipulihkan. Emergency backup berhasil dibuat terlebih dahulu.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | FUNGSI CREATE BACKUP
    |--------------------------------------------------------------------------
    */

    private function createBackup(
        string $mysqldump,
        string $database,
        string $username,
        ?string $password,
        string $backupPath
    ): array {

        $command = [

            $mysqldump,

            '--host=127.0.0.1',

            '--port=3306',

            '--user=' . $username,

        ];


        /*
        |--------------------------------------------------------------------------
        | PASSWORD
        |--------------------------------------------------------------------------
        */

        if (!empty($password)) {

            $command[] =
                '--password=' . $password;
        }


        /*
        |--------------------------------------------------------------------------
        | OPTIONS BACKUP
        |--------------------------------------------------------------------------
        */

        $command[] = '--single-transaction';

        $command[] = '--routines';

        $command[] = '--triggers';

        $command[] =
            '--result-file=' . $backupPath;

        $command[] = $database;


        /*
        |--------------------------------------------------------------------------
        | JALANKAN PROCESS
        |--------------------------------------------------------------------------
        */

        $process = new Process(
            $command,
            null,
            $this->getWindowsEnvironment()
        );


        $process->setTimeout(300);

        $process->run();


        /*
        |--------------------------------------------------------------------------
        | HASIL
        |--------------------------------------------------------------------------
        */

        if (!$process->isSuccessful()) {

            return [

                'success' => false,

                'error' =>
                $process->getErrorOutput()
                    ?: $process->getOutput(),

            ];
        }


        /*
        |--------------------------------------------------------------------------
        | PASTIKAN FILE BENAR-BENAR ADA
        |--------------------------------------------------------------------------
        */

        if (!file_exists($backupPath)) {

            return [

                'success' => false,

                'error' =>
                'File backup tidak berhasil dibuat.',

            ];
        }


        return [

            'success' => true,

            'error' => null,

        ];
    }


    /*
    |--------------------------------------------------------------------------
    | ENVIRONMENT WINDOWS
    |--------------------------------------------------------------------------
    */

    private function getWindowsEnvironment(): array
    {
        $environment = $_ENV;


        $environment['SYSTEMROOT'] =
            getenv('SYSTEMROOT')
            ?: 'C:\\Windows';


        $environment['WINDIR'] =
            getenv('WINDIR')
            ?: 'C:\\Windows';


        $environment['PATH'] =
            getenv('PATH')
            ?: '';


        return $environment;
    }
}
