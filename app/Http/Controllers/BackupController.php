<?php

namespace App\Http\Controllers;

use App\Models\Backup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

class BackupController extends Controller
{
  public function create()
  {
    try {
      // Check if we have 10 or more backups
      if (Backup::count() >= 10) {
        // Delete the oldest backup
        $oldestBackup = Backup::oldest()->first();
        Storage::delete('back-up/' . $oldestBackup->filename);
        $oldestBackup->delete();
      }

      // Create backup filename
      $filename = 'backup_' . Carbon::now()->format('Y-m-d_H-i-s') . '.sql';

      // Create backup using mysqldump
      $command = sprintf(
        'mysqldump -u%s -p%s %s > %s',
        config('database.connections.mysql.username'),
        config('database.connections.mysql.password'),
        config('database.connections.mysql.database'),
        storage_path('app/back-up/' . $filename)
      );

      exec($command);

      // Get file size
      $size = Storage::size('back-up/' . $filename);
      $formattedSize = $this->formatBytes($size);
      $path = 'back-up/' . $filename;
      // Save backup record
      Backup::create([
        'filename' => $filename,
        'size' => $formattedSize,
        'path' => $path,
      ]);

      return redirect()->back()->with('success', 'Backup created successfully');
    } catch (\Exception $e) {
      return redirect()->back()->with('error', 'Backup failed: ' . $e->getMessage());
    }
  }

  public function restore($id)
  {
    try {
      $backup = Backup::findOrFail($id);
      $filename = $backup->filename;

      // Restore backup using mysql
      $command = sprintf(
        'mysql -u%s -p%s %s < %s',
        config('database.connections.mysql.username'),
        config('database.connections.mysql.password'),
        config('database.connections.mysql.database'),
        storage_path('app/' . $backup->path)
      );

      exec($command);

      return redirect()->back()->with('success', 'Backup restored successfully');
    } catch (\Exception $e) {
      return redirect()->back()->with('error', 'Restore failed: ' . $e->getMessage());
    }
  }

  public function download($id)
  {
    $backup = Backup::findOrFail($id);
    return Storage::download($backup->path);
  }

  public function destroy($id)
  {
    try {
      $backup = Backup::findOrFail($id);
      Storage::delete($backup->path);
      $backup->delete();

      return redirect()->back()->with('success', 'Backup deleted successfully');
    } catch (\Exception $e) {
      return redirect()->back()->with('error', 'Delete failed: ' . $e->getMessage());
    }
  }

  private function formatBytes($bytes)
  {
    if ($bytes > 0) {
      $i = floor(log($bytes) / log(1024));
      $sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
      return sprintf('%.2f %s', $bytes / pow(1024, $i), $sizes[$i]);
    }
    return '0 B';
  }
}