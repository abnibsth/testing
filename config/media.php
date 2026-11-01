<?php

return [
    // Folder fisik tempat video disimpan (tanpa /iklan). Atur di .env tiap lingkungan:
    //   produksi: /home/novasgn/domains/vortexsign.web.id/public_html/media
    //   staging : /home/novasgn/domains/staging.vortexsign.web.id/public_html/media
    'path' => env('MEDIA_PATH', '/home/novasgn/domains/vortexsign.web.id/public_html/media'),
];
