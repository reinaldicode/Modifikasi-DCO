<?php
include 'koneksi.php';

// Function untuk dropdown Section (Tier One)
function getTierOne() {
    global $link;
    $sql = "SELECT DISTINCT sect_name FROM section ORDER BY sect_name";
    $result = mysqli_query($link, $sql);
    
    while($row = mysqli_fetch_array($result)) {
        echo '<option value="' . htmlspecialchars($row['sect_name']) . '">' . htmlspecialchars($row['sect_name']) . '</option>';
    }
}

// Handler untuk AJAX requests
if (isset($_GET['func'])) {
    $func = $_GET['func'];
    
    // Cascading: Device → Process
    if ($func == "device" && isset($_GET['drop_var2'])) {
        $device = mysqli_real_escape_string($link, $_GET['drop_var2']);
        
        if ($device == 'General Production') {
            echo '<select name="proc" class="form-control">
                    <option value=""> --- Select Process --- </option>
                    <option value="General PC">General PC</option>
                  </select>';
        } else {
            $sql = "SELECT DISTINCT process FROM docu WHERE device='$device' AND process != '' ORDER BY process";
            $result = mysqli_query($link, $sql);
            
            echo '<select name="proc" class="form-control">
                    <option value=""> --- Select Process --- </option>';
            
            while($row = mysqli_fetch_array($result)) {
                echo '<option value="' . htmlspecialchars($row['process']) . '">' . htmlspecialchars($row['process']) . '</option>';
            }
            
            echo '</select>';
        }
    }
    
    // Cascading: Section → Device
    if ($func == "section" && isset($_GET['drop_var'])) {
        $section = mysqli_real_escape_string($link, $_GET['drop_var']);
        
        $sql = "SELECT * FROM device WHERE status='Aktif' ORDER BY name";
        $result = mysqli_query($link, $sql);
        
        echo '<select id="device" name="device" class="form-control">
                <option value=""> --- Select Device --- </option>
                <option value="General Production">General Production</option>';
        
        while($row = mysqli_fetch_array($result)) {
            echo '<option value="' . htmlspecialchars($row['name']) . '">' . htmlspecialchars($row['name']) . '</option>';
        }
        
        echo '</select>';
    }
}
?>