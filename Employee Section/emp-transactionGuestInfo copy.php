




<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">

  <div class="card-body">
    <!-- Guest Cards Container -->
    <div class="guest-cards-container">
        <!-- Sample Guest Card - Table Row Style -->
        <div class="guest-card" onclick="showGuestDetails('G001')">
            <div class="card-content">
                <div class="profile-picture">
                    JM
                </div>
                <div class="card-data">
                    <div class="data-item">
                        <div class="data-label">Contact Name</div>
                        <div class="data-value primary">John Michael Smith Jr.</div>
                    </div>
                    <div class="data-item">
                        <div class="data-label">Birthdate</div>
                        <div class="data-value">March 15, 1998</div>
                    </div>
                    <div class="data-item">
                        <div class="data-label">Age</div>
                        <div class="data-value">25</div>
                    </div>
                    <div class="data-item">
                        <div class="data-label">Sex</div>
                        <div class="data-value">Male</div>
                    </div>
                    <div class="data-item">
                        <div class="data-label">Nationality</div>
                        <div class="data-value">American</div>
                    </div>
                    <div class="data-item">
                        <div class="data-label">Contact No</div>
                        <div class="data-value">+1 555-0123</div>
                    </div>
                    <div class="data-item">
                        <div class="data-label">Other Contact</div>
                        <div class="data-value">+1 555-0124</div>
                    </div>
                    <div class="data-item">
                        <div class="data-label">Email</div>
                        <div class="data-value">john.smith@email.com</div>
                    </div>
                    <div class="data-item">
                        <div class="data-label">Address</div>
                        <div class="data-value">123 Main St, Anytown, NY, 12345, USA</div>
                    </div>
                    <div class="data-item">
                        <div class="data-label">Passport No.</div>
                        <div class="data-value">P123456789</div>
                    </div>
                    <div class="data-item">
                        <div class="data-label">Passport Exp.</div>
                        <div class="data-value">Dec 31, 2026</div>
                    </div>
                    <div class="data-item">
                        <div class="data-label">Visa Status</div>
                        <div class="passport-status">
                            <i class="fas fa-check-circle"></i>
                            Valid
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sample Guest Card 2 -->
        <div class="guest-card" onclick="showGuestDetails('G002')">
            <div class="card-content">

                <div class="profile-picture">
                    ME
                </div>

                <div class="card-data">

                  <div>
                    <div class="data-item">
                        <div class="data-label">Contact Name</div>
                        <div class="data-value primary">Maria Elena Rodriguez</div>
                    </div>

                    <div class="data-item">
                        <div class="data-label">Birthdate</div>
                        <div class="data-value">July 22, 1991</div>
                    </div>
                    
                    <div class="data-item">
                        <div class="data-label">Age</div>
                        <div class="data-value">32</div>
                    </div>

                    <div class="data-item">
                        <div class="data-label">Sex</div>
                        <div class="data-value">Female</div>
                    </div>
                    
                    <div class="data-item">
                        <div class="data-label">Nationality</div>
                        <div class="data-value">Spanish</div>
                    </div>
                    <div class="data-item">
                        <div class="data-label">Contact No</div>
                        <div class="data-value">+34 666-555-0123</div>
                    </div>
                    <div class="data-item">
                        <div class="data-label">Other Contact</div>
                        <div class="data-value">N/A</div>
                    </div>
                    <div class="data-item">
                        <div class="data-label">Email</div>
                        <div class="data-value">maria.rodriguez@email.com</div>
                    </div>
                    <div class="data-item">
                        <div class="data-label">Address</div>
                        <div class="data-value">456 Barcelona St, Madrid, 28001, Spain</div>
                    </div>
                    <div class="data-item">
                        <div class="data-label">Passport No.</div>
                        <div class="data-value">ES987654321</div>
                    </div>
                    <div class="data-item">
                        <div class="data-label">Passport Exp.</div>
                        <div class="data-value">Jun 15, 2025</div>
                    </div>
                    <div class="data-item">
                        <div class="data-label">Visa Status</div>
                        <div class="passport-status">
                            <i class="fas fa-check-circle"></i>
                            Valid
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- No Requests Container (when no data) -->
        <!-- <div class='no-requests-container' onclick='redirectWithId(123)'>
            <div class='drag-drop-content'>
                <i class='fas fa-user-slash upload-icon'></i>
                <span class='main-text'>No Guest Information Found</span>
                <span class='accent-text'>Currently no guest info inserted.</span>
            </div>
        </div> -->
    </div>





    <!-- Offcanvas -->
    <div class="offcanvas-backdrop" onclick="hideGuestDetails()">
      <div class="offcanvas" id="guestOffcanvas">
          <div class="offcanvas-header">
              <h5 class="offcanvas-title">Guest Details</h5>
              <button type="button" class="btn-close" onclick="hideGuestDetails()">×</button>
          </div>
          <div class="offcanvas-body" id="offcanvasContent">
              <!-- Content will be dynamically inserted here -->
          </div>
      </div>
    </div>

    <script>
        function showGuestDetails(guestId) {
            const offcanvas = document.getElementById('guestOffcanvas');
            const backdrop = document.querySelector('.offcanvas-backdrop');
            const content = document.getElementById('offcanvasContent');
            
            content.innerHTML = `
                <div style="text-align: center; margin-bottom: 24px;">
                    <div class="profile-picture" style="width: 80px; height: 80px; font-size: 32px; margin: 0 auto 12px;">
                        ${guestId.substring(1)}
                    </div>
                    <h3 style="margin: 0; color: #1f2937;">Guest ID: ${guestId}</h3>
                    <p style="margin: 4px 0 0; color: #6b7280; font-weight: 500;">Loading guest details...</p>
                </div>
                
                <div class="detail-section">
                    <h4>Loading Information</h4>
                    <div style="text-align: center; padding: 20px; color: #6b7280;">
                        <i class="fas fa-spinner fa-spin" style="font-size: 24px; margin-bottom: 8px;"></i>
                        <p>Fetching guest information...</p>
                    </div>
                </div>
            `;
            
            backdrop.classList.add('show');
            offcanvas.classList.add('show');
            document.body.style.overflow = 'hidden';
            
            // Here you would make an AJAX call to fetch guest details
            // fetchGuestDetails(guestId);
        }
        
        function hideGuestDetails() {
            const offcanvas = document.getElementById('guestOffcanvas');
            const backdrop = document.querySelector('.offcanvas-backdrop');
            
            backdrop.classList.remove('show');
            offcanvas.classList.remove('show');
            document.body.style.overflow = 'auto';
        }
        
        // Function to fetch guest details via AJAX
        function fetchGuestDetails(guestId) {
            // Example AJAX call - replace with your actual endpoint
            /*
            fetch('fetch_guest_details.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'guestId=' + encodeURIComponent(guestId)
            })
            .then(response => response.json())
            .then(data => {
                updateOffcanvasContent(data);
            })
            .catch(error => {
                console.error('Error:', error);
            });
            */
        }
        
        // Close offcanvas on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                hideGuestDetails();
            }
        });
    </script>






  </div>
</div>


<!-- <script>
function redirectWithId(id) {
  window.location.href = "yourpage.php?id=" + id;
}
</script>

<span class='sub-text'>Click here to add a new guest</span> -->
