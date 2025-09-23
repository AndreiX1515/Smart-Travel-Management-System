<div class="tab-pane fade show active guestinfo-tab" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab"
    tabindex="0">
    <div class="card-body">

        <div class="guest-cards-container">

            <?php
                try {

                    // Simplified query using CONCAT_WS for cleaner concatenation
                    $sql = "SELECT 
                                guestId, fName, mName, lName, suffix,
                                DATE_FORMAT(birthdate, '%M %d, %Y') AS birthdate,
                                age, nationality,
                                CASE 
                                    WHEN countryCode IS NOT NULL AND contactNo IS NOT NULL 
                                    THEN CONCAT(TRIM(countryCode), ' ', TRIM(contactNo))
                                    ELSE NULL 
                                END AS contactNo,
                                emailAdd, passportNo, visaStatus
                            FROM guest 
                            WHERE transactNo = ?";

                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $transactNum);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            // Process guest data
                            $guest = processGuestData($row);
                            displayGuestCard($guest);
                        }
                    } else {
                        displayNoGuestCard();
                    }

                } catch (Exception $e) {
                    error_log("Guest fetch error: " . $e->getMessage());
                    echo "<div class='error-card'>Unable to load guest information.</div>";
                } finally {
                    if (isset($stmt)) $stmt->close();
                }

                /** Process and clean guest data */
                function processGuestData($row) {
                    // Build full name
                    $nameComponents = array_filter([
                        trim($row['fName'] ?? ''),
                        trim($row['mName'] ?? ''),
                        trim($row['lName'] ?? '')
                    ]);
                    $fullName = implode(' ', $nameComponents);
                    
                    if (!empty($row['suffix']) && $row['suffix'] !== 'N/A') {
                        $fullName .= ' ' . trim($row['suffix']);
                    }

                    // Generate initials
                    $firstName = trim($row['fName'] ?? '');
                    $lastName = trim($row['lName'] ?? '');
                    $initials = substr($firstName, 0, 1) . substr($lastName, 0, 1);
                    $initials = strtoupper($initials) ?: '??';

                    // Clean data with helper function
                    return [
                        'guestId' => clean($row['guestId']),
                        'fullName' => clean($fullName),
                        'initials' => $initials,
                        'age' => clean($row['age']),
                        'birthdate' => clean($row['birthdate']),
                        'nationality' => clean($row['nationality']),
                        'contactNo' => clean($row['contactNo']),
                        'emailAdd' => clean($row['emailAdd']),
                        'passportNo' => clean($row['passportNo']),
                        'visaStatus' => clean($row['visaStatus'], 'In Process'),
                        'statusClass' => getStatusClass($row['visaStatus']),
                        'statusIcon' => getStatusIcon($row['visaStatus'])
                    ];
                }

                /** Clean and sanitize data */
                function clean($value, $default = 'N/A') {
                    return empty($value) ? $default : htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
                }

                /** Get visa status CSS class */
                function getStatusClass($status) {
                    return (strtolower(trim($status ?? '')) === 'valid') ? 'valid' : 'in-process';
                }

                /** Get visa status icon */
                function getStatusIcon($status) {
                    return (strtolower(trim($status ?? '')) === 'valid') ? 'fa-check-circle' : 'fa-spinner';
                }

                /** Display guest card */
                function displayGuestCard($guest) {
                    echo "
                    <div class='guest-card' onclick='showGuestDetails({$guest['guestId']})'>
                        <div class='card-content'>
                            
                            <div class='profile-section'>
                                <div class='profile-picture'>";
                                        if (!empty($profileImage)) {
                                            echo "<img src='{$profileImage}' alt='Profile'>";
                                        } else {
                                            echo "<span>{$guest['initials']}</span>";
                                        }
                                echo "</div>


                                <div class='profile-name'>{$guest['fullName']}</div>
                            </div>

                            <div class='info-section'>
                                
                                <div class='info-row primary'>
                                    <div class='info-col third'>
                                        <div class='info-label'>Age</div>
                                        <div class='info-value primary'>{$guest['age']} years old</div>
                                    </div>
                                    <div class='info-col third'>
                                        <div class='info-label'>Birthdate</div>
                                        <div class='info-value primary'>{$guest['birthdate']}</div>
                                    </div>
                                    <div class='info-col third'>
                                        <div class='info-label'>Nationality</div>
                                        <div class='info-value primary'>{$guest['nationality']}</div>
                                    </div>
                                </div>

                                <div class='info-row primary'>
                                    <div class='info-col third'>
                                        <div class='info-label'>Contact</div>
                                        <div class='info-value'>{$guest['contactNo']}</div>
                                    </div>
                                    <div class='info-col third'>
                                        <div class='info-label'>Email</div>
                                        <div class='info-value'>{$guest['emailAdd']}</div>
                                    </div>
                                </div>

                                <div class='info-row secondary'>
                                    <div class='info-col third'>
                                        <div class='info-label'>Passport</div>
                                        <div class='info-value'>{$guest['passportNo']}</div>
                                    </div>
                                    <div class='info-col third'>
                                        <div class='info-label'>Passport Exp.</div>
                                        <div class='info-value'>--</div>
                                    </div>
                                    <div class='info-col third badge-visa-wrapper'>
                                        <div class='info-label'>Visa Status</div>
                                        <div class='status-badge {$guest['statusClass']}'>
                                            <i class='fas {$guest['statusIcon']}'></i>
                                            {$guest['visaStatus']}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>";
                }

                /**
                 * Display no guest found card
                 */
                function displayNoGuestCard() {
                    echo "
                    <div class='no-guest-card'>
                        <div class='no-guest-content'>
                            <i class='fas fa-user-slash no-guest-icon'></i>
                            <div class='no-guest-title'>No Guest Information Found</div>
                            <div class='no-guest-subtitle'>Currently no guest info inserted.</div>
                        </div>
                    </div>";
                }
                ?>
        </div>

        <script>
            function showGuestDetails(guestId) {
                console.log('Show guest details for:', guestId);
                // Add your guest details logic here
            }

            function redirectWithId(id) {
                console.log('Redirect with ID:', id);
                // Add your redirect logic here
            }
        </script>

    </div>
</div>

<!-- Offcanvas -->
    <div class="offcanvas-backdrop guestinfo-offcanvas" onclick="hideGuestDetails()">

        <div class="offcanvas" id="guestOffcanvas">

            <div class="offcanvas-header">
                <div class="guest-id-label">Guest ID </div>
                <div class="guest-id-value" id="guestIdDisplay">--</div>
                <button type="button" class="btn-close" onclick="hideGuestDetails()">×</button>
            </div>

            <div class="offcanvas-body">
                <div class="guest-id-display">
                    
                       
                </div>
            </div>
        </div>
    </div>


    <script>
        function showGuestDetails(guestId) {
            const offcanvas = document.getElementById('guestOffcanvas');
            const backdrop = document.querySelector('.offcanvas-backdrop');
            
            // Update the guest ID display
            document.getElementById('guestIdDisplay').textContent = guestId;
            
            // Show offcanvas
            backdrop.classList.add('show');
            offcanvas.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function hideGuestDetails() {
            const offcanvas = document.getElementById('guestOffcanvas');
            const backdrop = document.querySelector('.offcanvas-backdrop');
            
            backdrop.classList.remove('show');
            offcanvas.classList.remove('show');
            document.body.style.overflow = 'auto';
        }

        // Close offcanvas on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                hideGuestDetails();
            }
        });

        // Prevent closing when clicking inside offcanvas
        document.getElementById('guestOffcanvas').addEventListener('click', function(e) {
            e.stopPropagation();
        });
    </script>

<!-- <script>
function redirectWithId(id) {
  window.location.href = "yourpage.php?id=" + id;
}
</script>

<span class='sub-text'>Click here to add a new guest</span> -->