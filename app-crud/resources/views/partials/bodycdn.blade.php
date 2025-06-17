<!-- JQuery CDN -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- DataTables Core & Buttons JS -->
<script src="https://cdn.datatables.net/2.3.0/js/dataTables.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/3.2.3/js/dataTables.buttons.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/3.2.3/js/buttons.dataTables.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/3.2.3/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/3.2.3/js/buttons.print.min.js"></script>


<script>
    //Select2 Initialiser
    $(document).ready(function() {
        
        //Bootstrap Tooltip Initiliser
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

        const notificationsDropdownToggle = document.getElementById('notificationsDropdownToggle');
        const notificationsList = document.getElementById('notifications-list');
        const notificationsLoading = document.getElementById('notifications-loading');
        const noNotificationsMessage = document.getElementById('no-notifications');
        const unreadCountBadge = document.getElementById('unread-notifications-count');
        const markAllAsReadBtn = document.getElementById('mark-all-as-read-btn');

        function updateUnreadCount(count) {
            if (count > 0) {
                unreadCountBadge.textContent = count;
                unreadCountBadge.style.display = 'block';
            } else {
                unreadCountBadge.textContent = '0';
                unreadCountBadge.style.display = 'none';
            }
        }

        async function fetchNotifications() {
            const existingDynamicItems = notificationsList.querySelectorAll('.notification-item');
            existingDynamicItems.forEach(item => item.remove());

            const headerLi = notificationsList.querySelector('.dropdown-header');
            const hrContainerLi = headerLi ? headerLi.nextElementSibling : null;
            const targetInsertionPoint = hrContainerLi ? hrContainerLi.querySelector('hr') : null;

            if (!targetInsertionPoint) {
                console.error(
                    "Error: Could not find the target insertion point for notifications. HTML structure might be unexpected."
                );
                notificationsLoading.textContent = 'Error: Invalid dropdown structure.';
                notificationsLoading.style.display = 'block';
                noNotificationsMessage.style.display = 'none';
                updateUnreadCount(0);
                return;
            }

            notificationsLoading.style.display = 'block';
            noNotificationsMessage.style.display = 'none';
            unreadCountBadge.style.display = 'none';

            targetInsertionPoint.insertAdjacentElement('afterend', noNotificationsMessage);
            targetInsertionPoint.insertAdjacentElement('afterend', notificationsLoading);

            try {
                const response = await fetch('{{ route('notifications.unread') }}', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                });

                if (!response.ok) {
                    if (response.status === 401) {
                        console.error('Unauthorized: User not authenticated.');
                    } else {
                        console.error(`HTTP error! status: ${response.status}`);
                    }
                    throw new Error('Failed to fetch notifications.');
                }

                const notifications = await response.json();

                notificationsLoading.style.display = 'none';

                if (notifications.length > 0) {
                    let notificationItemsHtml = '';
                    notifications.forEach(notification => {
                        const title = notification.data.title || 'New Notification';
                        const message = notification.data.message || 'You have a new update.';
                        const link = notification.data.link || '#';
                        const createdAt = new Date(notification.created_at).toLocaleString();

                        notificationItemsHtml += `
                        <li class="notification-item border">
                            <a class="dropdown-item" href="${link}" data-notification-id="${notification.id}">
                                <p class="mb-1"><strong>${title}</strong></p>
                                <p class="mb-1 text-muted text-wrap small">${message}</p>
                                <p class="mb-0 text-secondary small">${createdAt}</p>
                            </a>
                        </li>
                    `;
                    });
                    targetInsertionPoint.insertAdjacentHTML('afterend', notificationItemsHtml);

                    updateUnreadCount(notifications.length);
                    markAllAsReadBtn.parentNode.style.display = 'block';
                } else {
                    noNotificationsMessage.style.display = 'block';
                    updateUnreadCount(0);
                    markAllAsReadBtn.parentNode.style.display = 'none';
                }
            } catch (error) {
                console.error('Error fetching notifications:', error);
                notificationsLoading.textContent = 'Error loading notifications.';
                notificationsLoading.style.display = 'block';
                updateUnreadCount(0);
                markAllAsReadBtn.parentNode.style.display = 'none';
            }
        }

        async function markAllAsRead() {
            try {
                const response = await fetch('{{ route('notifications.markAllAsRead') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                console.log(result.message);

                fetchNotifications(); // Refresh UI after marking as read

            } catch (error) {
                console.error('Network error marking notifications as read:', error);
                alert('Failed to mark all notifications as read. Please try again.');
            }
        }

        // NEW: Event listener for marking a single notification as read and redirecting
        notificationsList.addEventListener('click', async function(event) {
            // Use .closest() to find the nearest ancestor that is an 'a.dropdown-item' with 'data-notification-id'
            const clickedLink = event.target.closest('a.dropdown-item[data-notification-id]');

            if (clickedLink) {
                event
                    .preventDefault(); // Prevent default redirection until notification is marked read

                const notificationId = clickedLink.dataset.notificationId;
                const originalHref = clickedLink.href;

                try {
                    const response = await fetch(
                        `/notifications/${notificationId}/mark-as-read-single`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                            }
                        });

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const result = await response.json();
                    console.log(result.message);

                    // Re-fetch notifications to update the UI (remove the now-read notification)
                    await fetchNotifications();

                    // Finally, redirect the user to the original link
                    if (originalHref && originalHref !== '#') {
                        window.location.href = originalHref;
                    } else {
                        // If no specific link, just hide the dropdown
                        const bsDropdown = bootstrap.Dropdown.getInstance(
                            notificationsDropdownToggle);
                        if (bsDropdown) {
                            bsDropdown.hide();
                        }
                    }

                } catch (error) {
                    console.error('Error marking specific notification as read:', error);
                    alert('Failed to mark notification as read, but redirecting...'); // Inform user
                    // Even if marking fails, still redirect for user experience
                    if (originalHref && originalHref !== '#') {
                        window.location.href = originalHref;
                    }
                }
            }
        });

        notificationsDropdownToggle.addEventListener('show.bs.dropdown', fetchNotifications);
        markAllAsReadBtn.addEventListener('click', function(event) {
            event.preventDefault();
            markAllAsRead();
        });

        fetchNotifications(); // Initial fetch for the badge count on page load
    });
</script>
