<?php 


?>


<div class="confirm-container">
              <div class="table-header">
                <h6 class="white-pill">Confirmed Transactions</h6>
              </div>

              <div class="table-container confirm-table-container">
                <table class="confirm-table" id="confirm-table">
                  <thead>
                    <tr>
                      <th>TRANSACTION NO.</th>
                      <th>AGENT NAME</th>
                      <th>PACKAGE</th>
                      <th>FLIGHT DATE</th>
                      <th>TOTAL PAX.</th>
                      <th>BOOKING TYPE</th>
                      <th>STATUS</th>
                      <th>COMMENT</th>

                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $query1 = "SELECT b.*, f.flightDepartureDate AS Start, p.packageName,
                      f.returnDepartureDate AS End, CONCAT(a.lName, ', ', a.fName, 
                      IF(a.mName IS NOT NULL AND a.mName != '', CONCAT(' ', LEFT(a.mName, 1)), '')) AS agentName,
                      br.branchName as branchName
              FROM booking b 
              JOIN agent a ON b.agentId = a.agentId
              JOIN branch br ON b.agentCode = br.branchAgentCode
              JOIN flight f ON b.flightId = f.flightId
              JOIN package p ON b.packageId = p.packageId
              WHERE status = 'Confirmed'";

                    $result = $conn->query($query1);

                    // Check if the query returned any results
                    if ($result && $result->num_rows > 0) {
                      while ($row = $result->fetch_assoc()) {
                        $status = $row['status'];

                        // Define the pill status class based on the status value
                        switch ($status) {
                          case 'Confirmed':
                            $pillClass = 'bg-success';
                            break;
                          case 'Cancelled':
                            $pillClass = 'bg-danger';
                            break;
                          case 'Pending':
                            $pillClass = 'bg-warning';
                            break;
                          case 'Rejected':
                            $pillClass = 'bg-info';
                            break;
                          default:
                            $pillClass = 'bg-secondary';
                            break;
                        }

                        // Generate the table row with dynamically set `recordId`
                        echo "<tr data-id='{$row['transactNo']}'> <!-- Set the row ID dynamically -->
                                <td>{$row['transactNo']}</td>
                                <td>{$row['branchName']}</td>
                                <td>{$row['packageName']}</td>
                                <td>{$row['Start']}</td>
                                <td>{$row['pax']}</td>
                                <td>{$row['bookingType']}</td>
                                <td>
                                    <span class='badge $pillClass p-2'>{$status}</span>
                                </td>";

                        // Fetching the comment from the database
                        $transactNo = $row['transactNo'];
                        $stmt = $conn->prepare('SELECT comment FROM bookingcomments WHERE transactNo = ?');
                        $stmt->bind_param('s', $transactNo);
                        $stmt->execute();
                        $resultComment = $stmt->get_result();
                        $comment = $resultComment->fetch_assoc();
                        $stmt->close();

                        echo "<td>";
                        echo '<div class="comment-container" id="commentContainer' . $transactNo . '">';

                        // Check if a comment exists
                        if ($comment && !empty($comment['comment'])) {
                          // If a comment exists, display it and show the 'Edit' button
                          echo '<div class="comment-exists">
                                    <div class="comment-input">
                                        <input type="text" class="form-control" name="comment" id="commentInput' . $transactNo . '" value="' . htmlspecialchars($comment['comment']) . '" disabled>
                                    </div>
                                    <div class="edit-button">
                                        <button type="button" class="btn btn-warning editComment" data-id="' . $transactNo . '">Edit</button>
                                    </div>
                                  </div>';
                        } else {
                          // If no comment exists, show input for adding a new comment
                          echo '<div class="no-comment">
                                    <div class="comment-input">
                                        <input type="text" class="form-control" name="comment" id="commentInput' . $transactNo . '" placeholder="Add a comment" disabled>
                                    </div>
                                    <div class="add-button">
                                        <button type="button" class="btn btn-success addComment" data-id="' . $transactNo . '">Add</button>
                                    </div>
                                  </div>';
                        }

                        echo '</div>'; // Close the comment-container div

                        // Action buttons (initially hidden where needed)
                        echo '<div class="button-container">
                                <input type="text" class="recordId" value="' . $row['transactNo'] . '" hidden>

                                <!-- Submit buttons for add/edit -->
                                <button type="button" class="btn btn-primary submitAddComment" data-id="' . $transactNo . '" style="display: none;">Submit</button>

                                <button type="button" class="btn btn-primary submitEditComment" data-id="' . $transactNo . '" style="display: none;">Update</button>

                                <!-- Cancel buttons -->
                                <button type="button" class="btn btn-danger cancelEditComment" data-id="' . $transactNo . '" style="display: none;">Cancel Edit</button>

                                <button type="button" class="btn btn-danger cancelAddComment" data-id="' . $transactNo . '" style="display: none;">Cancel Add</button>
                              </div>';

                        echo "</td>"; // Close the <td> tag


                        echo "</tr>";
                      }
                    } else {
                      // No records found
                      echo "<tr><td colspan='7'>No confirmed bookings found.</td></tr>";
                    }

                    if ($result) {
                      $result->free();
                    }

                    $conn->close();
                    ?>
                  </tbody>
                </table>
              </div>

            </div>
