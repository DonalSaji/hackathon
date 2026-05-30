<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Vendor js -->
<script src="{{ asset('assets/js/vendor.min.js') }}"></script>

<!-- Toast Plugin js -->
<script src="{{ asset('assets/vendor/jquery-toast-plugin/jquery.toast.min.js') }}"></script>

<!-- Toastr Demo js -->
<script src="{{ asset('assets/js/pages/demo.toastr.js') }}"></script>

@stack('custom-scripts')

<!-- App js -->
<script src="{{ asset('assets/js/app.min.js') }}"></script>





<!-- Bootstrap JS Bundle with Popper for modals-->
<!--<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/js/bootstrap.bundle.min.js"></script>-->

@if (session('success'))
    <script>
        $(document).ready(function() {
            $.NotificationApp.send(
                "Success",
                "{{ session('success') }}",
                "top-right",
                "rgba(0,0,0,0.2)",
                "success"
            );
        });
    </script>
@endif

@if (session('error'))
    <script>
        $(document).ready(function() {
            $.NotificationApp.send(
                "Error",
                "{{ session('error') }}",
                "top-right",
                "rgba(0,0,0,0.2)",
                "error"
            );
            console.log("{{ session('error') }}");
        });
    </script>
@endif

<script>
    $(".button-toggle-menu").click(function() {
        if ($.fn.DataTable.isDataTable(".table")) { // Check if DataTable is initialized
            var table = $(".table").DataTable();
            setTimeout(function() {
                table.columns.adjust().draw(false); // Adjust columns without resetting paging
            }, 200); // Delay to match sidebar animation
        }
    });

    var responseMessage = sessionStorage.getItem('responseMessage');
    if (responseMessage) {
        $.NotificationApp.send(
            "Success",
            responseMessage,
            "top-right",
            "rgba(0,0,0,0.2)",
            "success"
        );
        sessionStorage.removeItem('responseMessage');
    }


    document.addEventListener("DOMContentLoaded", function() {
        // 🔹 General function to correct sidenav layout
        function fixSidenavLayout() {
            const html = document.documentElement;
            if (window.innerWidth < 768) {
                html.setAttribute('data-sidenav-size', 'full');
            }
        }

        // 🔹 Run immediately and on window resize
        fixSidenavLayout();
        window.addEventListener('resize', fixSidenavLayout);

        // 🔹 Detect any DataTable draw events on the page
        $(document).on('draw.dt', function() {
            fixSidenavLayout();
        });

        // Optional: also trigger after any AJAX complete (DataTables async reloads)
        $(document).ajaxComplete(function() {
            fixSidenavLayout();
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var isLoading = false;
        var page = 1;
        var notificationList = document.getElementById('noti-list');

        function getRelativeDateLabel(dateStr) {
            var date = new Date(dateStr.replace(' ', 'T'));
            var today = new Date();
            var yesterday = new Date(today);
            yesterday.setDate(yesterday.getDate() - 1);

            // Normalize dates to compare only dates
            var compareDate = new Date(date.getFullYear(), date.getMonth(), date.getDate());
            var compareToday = new Date(today.getFullYear(), today.getMonth(), today.getDate());
            var compareYesterday = new Date(yesterday.getFullYear(), yesterday.getMonth(), yesterday.getDate());

            if (compareDate.getTime() === compareToday.getTime()) {
                return 'Today';
            } else if (compareDate.getTime() === compareYesterday.getTime()) {
                return 'Yesterday';
            } else {
                return date.getDate() + ' ' + date.toLocaleString('en-IN', {
                    month: 'long'
                }) + ' ' + date.getFullYear();
            }
        }

        function createDateHeading(label) {
            var heading = '<h5 class="text-muted font-13 fw-normal mt-2">' + label + '</h5>';
            notificationList.insertAdjacentHTML('beforeend', heading);
            return label;
        }

        function appendNotification(notification, isRealtime = false) {
            var rawDate = notification.created_at ?? new Date().toISOString();
            var dateObj = new Date(typeof rawDate === 'string' ? rawDate.replace(' ', 'T') : rawDate);
            var formattedTime = dateObj.toLocaleTimeString('en-IN', {
                hour: 'numeric',
                minute: 'numeric',
                hour12: true
            });

            // Use relative date labels (Today/Yesterday)
            var dateLabel = getRelativeDateLabel(rawDate);
            const {
                link,
                icon,
                bg_icon,
                title,
                body
            } = notification.data;

            // Add unread badge on bell
            if (!notification || notification.read_at == null) {
                document.getElementsByClassName('unreadDot')[0]?.classList.add('noti-icon-badge');
            }
            var notificationElement =
                '<a href="' + "{{ url('') }}" + link +
                '" class="dropdown-item p-0 notify-item card ' +
                ((!notification || notification.read_at == null) ? "unread-noti" : "read-noti") +
                ' shadow-none mb-2">' +
                '<div class="card-body">' +
                '<div class="d-flex align-items-center">' +
                '<div class="flex-shrink-0">' +
                '<div class="notify-icon ' + (bg_icon || notification.color) + '">' +
                '<i class="' + icon + '"></i>' +
                '</div>' +
                '</div>' +
                ' <div class="flex-grow-1 text-truncate ms-2">' +
                '<h5 class="noti-item-title fw-semibold font-14">' + title +
                '<small class="fw-normal text-muted ms-1">' + formattedTime + '</small></h5>' +
                '<small class="noti-item-subtitle text-muted text-break text-wrap" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; white-space: normal;">' +
                body + '</small>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</a>';

            if (isRealtime) {
                // REAL-TIME notification: Insert at the top
                insertRealtimeNotification(dateLabel, notificationElement);
            } else {
                // INITIAL LOAD: Append in chronological order (oldest to newest)
                insertHistoricalNotification(dateLabel, notificationElement);
            }
        }

        function insertRealtimeNotification(dateLabel, notificationElement) {
            // For real-time notifications, insert at the top
            var firstHeading = notificationList.querySelector('h5');

            if (!firstHeading) {
                // No headings yet, create one and add notification
                createDateHeading(dateLabel);
                notificationList.insertAdjacentHTML('beforeend', notificationElement);
            } else if (firstHeading.textContent.trim() === dateLabel) {
                // Same date as current top, insert after heading but before next heading/notification
                firstHeading.insertAdjacentHTML('afterend', notificationElement);
            } else {
                // New date, create heading at top and insert notification
                notificationList.insertAdjacentHTML('afterbegin',
                    '<h5 class="text-muted font-13 fw-normal mt-2">' + dateLabel + '</h5>');
                var newHeading = notificationList.querySelector('h5');
                newHeading.insertAdjacentHTML('afterend', notificationElement);
            }
        }

        function insertHistoricalNotification(dateLabel, notificationElement) {
            // For historical data, append in order
            var headings = notificationList.querySelectorAll('h5');
            var targetHeading = null;

            // Find the heading for this date
            headings.forEach(function(heading) {
                if (heading.textContent.trim() === dateLabel) {
                    targetHeading = heading;
                }
            });

            if (targetHeading) {
                // Find the last element under this heading
                var lastElement = targetHeading;
                while (lastElement.nextElementSibling &&
                    lastElement.nextElementSibling.tagName !== 'H5') {
                    lastElement = lastElement.nextElementSibling;
                }
                lastElement.insertAdjacentHTML('afterend', notificationElement);
            } else {
                // New date heading needed
                createDateHeading(dateLabel);
                notificationList.insertAdjacentHTML('beforeend', notificationElement);
            }
        }

        function fetchNotifications() {
            if (isLoading || page === 0) return;
            isLoading = true;

            var xhr = new XMLHttpRequest();
            xhr.open('GET', '/notifications?page=' + page, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.data.length > 0) {
                            response.data.forEach(function(notification) {
                                appendNotification(notification, false);
                            });
                            page++;
                        } else {
                            page = 0; // no more pages
                        }
                    } else {
                        console.error('Error fetching notifications:', xhr.statusText);
                    }
                    isLoading = false;
                }
            };
            xhr.send();
        }

        // Infinite scroll
        notificationList.addEventListener('scroll', function() {
            var scrollTop = notificationList.scrollTop;
            var scrollHeight = notificationList.scrollHeight;
            var clientHeight = notificationList.clientHeight;

            if (scrollTop + clientHeight >= scrollHeight - 100) {
                fetchNotifications();
            }
        });

        // Load initial notifications
        fetchNotifications();

        // Real-time notifications
        const userId = document.head.querySelector('meta[name="user-id"]')?.content;
        if (userId && window.Echo) {
            //console.log('Attempting to subscribe to channel:', `notifications.${userId}`);

            const channel = Echo.private(`notifications.${userId}`);

            channel.listen('.UserAlert', function(e) {

                // Map the broadcast data to your expected format
                const notification = {
                    data: {
                        link: e.link,
                        icon: e.icon,
                        bg_icon: e.bg_icon,
                        title: e.title,
                        body: e.body
                    },
                    created_at: new Date().toISOString(),
                    pivot: {
                        read_at: null
                    }
                };
                appendNotification(notification, true);
            });

            // Separate the subscription events
            channel.subscribed(() => {
                //console.log('✅ Successfully subscribed to private channel!');
            });

            channel.error((error) => {
                console.error('❌ Subscription error:', error);
            });

        } else {
            console.error('Cannot subscribe: ', {
                hasUserId: !!userId,
                hasEcho: !!window.Echo,
                userId: userId
            });
        }
    });

    $(document).ready(function() {
        $(".dropdown.notification-list").click(function() {
            if ($(".unreadDot").hasClass("noti-icon-badge")) {
                const csrfToken = document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content");

                $.ajax({
                    url: '{{ url('') }}/readnotifications', // Form action URL
                    type: "POST", // Form method (POST, GET, etc.)
                    headers: {
                        "X-CSRF-Token": csrfToken,
                    },
                    success: function(response) {
                        // Handle the successful response here
                        if (response.status)
                            $(".unreadDot").removeClass("noti-icon-badge");
                    },
                    error: function(xhr) {
                        if (xhr.status == 419)
                            window.location.href = '{{ url('') }}/login';
                    }
                });
            }
        });
    });
</script>

{{-- pwa notification  --}}
<script>
    if ("serviceWorker" in navigator && "PushManager" in window) {
        navigator.serviceWorker.register("/sw.js").then(async (reg) => {
            //console.log("Service worker registered:", reg);

            const permission = await Notification.requestPermission();
            if (permission !== "granted") {
                console.warn("Notification permission not granted");
                return;
            }

            const vapidPublicKey = "{{ config('webpush.vapid.public_key') }}";
            const sub = await reg.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array(vapidPublicKey)
            });

            await fetch("/subscribe", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    endpoint: sub.endpoint,
                    keys: {
                        p256dh: arrayBufferToBase64(sub.getKey("p256dh")),
                        auth: arrayBufferToBase64(sub.getKey("auth"))
                    }
                })
            });

            //console.log("Push subscription sent to server ✅");
        }).catch((err) => console.error("Service Worker error:", err));
    }

    function urlBase64ToUint8Array(base64String) {
        const padding = "=".repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding)
            .replace(/-/g, "+")
            .replace(/_/g, "/");
        const rawData = atob(base64);
        return Uint8Array.from([...rawData].map((c) => c.charCodeAt(0)));
    }

    function arrayBufferToBase64(buffer) {
        return btoa(String.fromCharCode.apply(null, new Uint8Array(buffer)));
    }
</script>
