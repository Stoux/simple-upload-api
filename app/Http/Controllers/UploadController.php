<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class UploadController extends Controller {

    private const UPLOADS_DIR = 'uploads';
    private const SAVED_DIR = 'saved';

    public function handleUpload( Request $request ) {
        $path = $request->file( 'file' )->store( self::UPLOADS_DIR );

        Log::info( "File uploaded", [
            'path' => $path,
        ] );

        return response( [ 'file' => preg_replace( '~^' . self::UPLOADS_DIR . '/~', '', $path ) ] );
    }

    public function listUploads() {
        return $this->listFilesIn( self::UPLOADS_DIR );
    }

    public function moveUpload( Request $request ) {
        $validated = $request->validate( [
            'upload' => 'string|required',
            'saved'  => 'string|required',
        ] );

        $from = self::UPLOADS_DIR . '/' . $validated['upload'];
        $to   = self::SAVED_DIR . '/' . $validated['saved'];

        if ( ! Storage::exists( $from ) ) {
            throw new BadRequestException( 'Upload path is invalid.' );
        }

        if ( Storage::exists( $to ) ) {
            throw new BadRequestException( 'Target path already exists' );
        }

        Storage::move( $from, $to );

        return [ 'moved' => $to ];
    }

    public function listSaved( Request $request ) {
        return $this->listFilesIn( self::SAVED_DIR, ! ! $request->get( 'dirs' ) );
    }

    /**
     * @param string $dir
     *
     * @return array
     */
    protected function listFilesIn( string $dir, bool $dirsOnly = false ): array {
        return array_map(
            fn( $path ) => preg_replace( '~^' . $dir . '/~', '', $path ),
            $dirsOnly ? Storage::allDirectories( $dir ) : Storage::allFiles( $dir )
        );
    }

}
