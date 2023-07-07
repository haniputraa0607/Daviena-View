<!DOCTYPE html>
<html>
<body>
    @php
        $last_key = count($connectors) - 1;
        foreach ($connectors as $key => $connector) {
            if ($key != $last_key) {
                echo '<div style="width: 100%"><img src="' . env('STORAGE_URL_API') . $connector['qr_path'] . '" style="width: 100%"/></div><div class="page-break"></div>';
            } else {
                echo '<div style="width: 100%"><img src="' . env('STORAGE_URL_API') . $connector['qr_path'] . '" style="width: 100%"/></div>';
            }
        }
    @endphp
</body>
</html>
