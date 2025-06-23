<?php
ob_start();
session_start();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar Pengingat</title>
    <style>
    :root {
        --bg-color: #f3f4f6;
        --text-color: #374151;
        --text-muted-color: #6b7280;
        --header-color: #1f2937;
        --card-bg: white;
        --card-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        --border-color: #e5e7eb;
        --border-color-light: #d1d5db;
        --primary-color: #3b82f6;
        --primary-hover-color: #2563eb;
        --danger-color: #ef4444;
        --danger-hover-color: #dc2626;
        --hover-bg-color: #f9fafb;
        --selected-bg-color: #dbeafe;
        --disabled-bg-color: #f9fafb;
        --input-focus-border: #3b82f6;
        --input-focus-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    body.dark-mode {
        --bg-color: #111827;
        --text-color: #d1d5db;
        --text-muted-color: #9ca3af;
        --header-color: #f9fafb;
        --card-bg: #1f2937;
        --card-shadow: 0 1px 3px rgba(0, 0, 0, 0.4);
        --border-color: #374151;
        --border-color-light: #4b5563;
        --primary-color: #3b82f6;
        --primary-hover-color: #60a5fa;
        --danger-color: #ef4444;
        --danger-hover-color: #f87171;
        --hover-bg-color: #374151;
        --selected-bg-color: #374151;
        --disabled-bg-color: #374151;
        --input-focus-border: #60a5fa;
        --input-focus-shadow: 0 0 0 3px rgba(96, 165, 250, 0.2);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background-color: var(--bg-color);
        color: var(--text-color);
        line-height: 1.6;
        transition: background-color 0.3s, color 0.3s;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 1rem;
    }

    .header {
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        margin-bottom: 2rem;
        position: relative;
    }

    .header h1 {
        font-size: 2rem;
        font-weight: bold;
        color: var(--header-color);
    }

    #theme-toggle {
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        background: var(--card-bg);
        border: 1px solid var(--border-color-light);
        border-radius: 0.375rem;
        padding: 0.5rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 2.5rem;
        height: 2.5rem;
        color: var(--text-color);
    }

    #theme-toggle:hover {
        background-color: var(--hover-bg-color);
    }

    .main-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    @media (min-width: 768px) {
        .main-grid {
            grid-template-columns: 2fr 1fr;
        }
    }

    .card {
        background: var(--card-bg);
        border-radius: 0.5rem;
        box-shadow: var(--card-shadow);
        padding: 1.5rem;
        transition: background-color 0.3s;
    }

    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .nav-button {
        background: var(--card-bg);
        border: 1px solid var(--border-color-light);
        color: var(--text-color);
        border-radius: 0.375rem;
        padding: 0.5rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 2.5rem;
        height: 2.5rem;
        transition: all 0.2s;
    }

    .nav-button:hover {
        background-color: var(--hover-bg-color);
    }

    .month-year {
        font-size: 1.25rem;
        font-weight: 600;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 1px;
        background-color: var(--border-color);
        border: 1px solid var(--border-color);
    }

    .day-header {
        background: var(--hover-bg-color);
        padding: 0.5rem;
        text-align: center;
        font-weight: 500;
        font-size: 0.875rem;
    }

    .calendar-day {
        background: var(--card-bg);
        min-height: 4rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        position: relative;
        transition: background-color 0.2s;
    }

    .calendar-day:hover {
        background-color: var(--hover-bg-color);
    }

    .calendar-day.selected {
        background-color: var(--selected-bg-color);
        border: 2px solid var(--primary-color);
    }

    .calendar-day.empty {
        cursor: default;
        background-color: var(--disabled-bg-color);
    }

    .calendar-day.empty:hover {
        background-color: var(--disabled-bg-color);
    }

    .reminder-dot {
        position: absolute;
        bottom: 0.25rem;
        width: 0.5rem;
        height: 0.5rem;
        background-color: var(--primary-color);
        border-radius: 50%;
    }

    .sidebar {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        font-size: 0.875rem;
    }

    .form-input {
        width: 100%;
        padding: 0.5rem 0.75rem;
        border: 1px solid var(--border-color-light);
        border-radius: 0.375rem;
        font-size: 0.875rem;
        background-color: var(--bg-color);
        color: var(--text-color);
        transition: border-color 0.2s;
    }

    .form-input:focus {
        outline: none;
        border-color: var(--input-focus-border);
        box-shadow: var(--input-focus-shadow);
    }

    .form-input:disabled {
        background-color: var(--disabled-bg-color);
        cursor: not-allowed;
    }

    .form-textarea {
        resize: vertical;
        min-height: 4rem;
    }

    .btn {
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-primary {
        background-color: var(--primary-color);
        color: white;
    }

    .btn-primary:hover {
        background-color: var(--primary-hover-color);
    }

    .btn-danger {
        background-color: var(--danger-color);
        color: white;
        padding: 0.25rem;
        width: 2rem;
        height: 2rem;
    }

    .btn-danger:hover {
        background-color: var(--danger-hover-color);
    }

    .btn-full {
        width: 100%;
    }

    .reminder-item {
        border: 1px solid var(--border-color);
        border-radius: 0.375rem;
        padding: 0.75rem;
        margin-bottom: 0.75rem;
        transition: background-color 0.2s;
    }

    .reminder-item:hover {
        background-color: var(--hover-bg-color);
    }

    .reminder-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.5rem;
    }

    .reminder-title {
        font-weight: 500;
        margin: 0;
        color: var(--header-color);
    }

    .reminder-time {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        color: var(--text-muted-color);
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .reminder-description {
        color: var(--text-muted-color);
        font-size: 0.875rem;
        margin: 0;
    }

    .no-reminders {
        text-align: center;
        color: var(--text-muted-color);
        padding: 2rem 0;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .icon {
        width: 1rem;
        height: 1rem;
        stroke: currentColor;
        fill: none;
    }

    .icon-lg {
        width: 1.25rem;
        height: 1.25rem;
    }

    .icon-sm {
        width: 0.75rem;
        height: 0.75rem;
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Calendar Pengingat</h1>
            <button id="theme-toggle" title="Toggle dark mode">
                <svg id="theme-icon-dark" class="icon icon-lg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                </svg>
                <svg id="theme-icon-light" class="icon icon-lg" fill="currentColor" viewBox="0 0 20 20"
                    style="display: none;">
                    <path
                        d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 14.95a1 1 0 010-1.414l.707-.707a1 1 0 011.414 1.414l-.707.707a1 1 0 01-1.414 0zM3 11a1 1 0 100-2H2a1 1 0 100 2h1z">
                    </path>
                </svg>
            </button>
        </div>

        <div class="main-grid">
            <div class="card">
                <div class="calendar-header">
                    <button class="nav-button" id="prevMonth" title="Previous Month"><svg class="icon"
                            viewBox="0 0 24 24">
                            <path d="M15 18l-6-6 6-6" />
                        </svg></button>
                    <h2 class="month-year" id="monthYear"></h2>
                    <button class="nav-button" id="nextMonth" title="Next Month"><svg class="icon" viewBox="0 0 24 24">
                            <path d="M9 18l6-6-6-6" />
                        </svg></button>
                </div>
                <div class="calendar-grid" id="calendar">
                    <div class="day-header">Min</div>
                    <div class="day-header">Sen</div>
                    <div class="day-header">Sel</div>
                    <div class="day-header">Rab</div>
                    <div class="day-header">Kam</div>
                    <div class="day-header">Jum</div>
                    <div class="day-header">Sab</div>
                </div>
            </div>

            <div class="sidebar">
                <div class="card">
                    <h2 class="section-title">Tambah Pengingat</h2>
                    <form id="reminderForm">
                        <div class="form-group"><label class="form-label" for="date">Tanggal</label><input type="date"
                                id="date" class="form-input" disabled></div>
                        <div class="form-group"><label class="form-label" for="time">Waktu</label><input type="time"
                                id="time" class="form-input" value="12:00" required></div>
                        <div class="form-group"><label class="form-label" for="title">Judul</label><input type="text"
                                id="title" class="form-input" placeholder="Masukkan judul pengingat" required></div>
                        <div class="form-group"><label class="form-label" for="description">Deskripsi</label><textarea
                                id="description" class="form-input form-textarea"
                                placeholder="Masukkan deskripsi (opsional)" rows="3"></textarea></div>
                        <div class="form-group"><label class="form-label" for="recipient">Kirim ke (JID
                                Pengguna)</label><input type="text" id="recipient" class="form-input"
                                placeholder="contoh@jabb.im" required></div>
                        <button type="submit" class="btn btn-primary btn-full">Tambah Pengingat</button>
                    </form>
                </div>
                <div class="card">
                    <h2 class="section-title">Pengingat Hari Ini</h2>
                    <div id="reminderList">
                        <div class="no-reminders">Tidak ada pengingat untuk tanggal ini</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    class CalendarApp {
        constructor() {
            this.currentMonth = new Date();
            this.selectedDate = new Date();
            this.reminders = [];
            this.monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                'September', 'Oktober', 'November', 'Desember'
            ];
            this.init();
        }

        async init() {
            this.initTheme();
            this.bindEvents();
            await this.loadReminders();
            this.updateCalendar();
            this.updateSelectedDate();
            this.updateReminderList();
        }

        initTheme() {
            this.themeToggleBtn = document.getElementById('theme-toggle');
            this.darkIcon = document.getElementById('theme-icon-dark');
            this.lightIcon = document.getElementById('theme-icon-light');
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark') {
                document.body.classList.add('dark-mode');
                this.darkIcon.style.display = 'none';
                this.lightIcon.style.display = 'block';
            }
            this.themeToggleBtn.addEventListener('click', () => this.toggleTheme());
        }

        toggleTheme() {
            document.body.classList.toggle('dark-mode');
            const isDarkMode = document.body.classList.contains('dark-mode');
            localStorage.setItem('theme', isDarkMode ? 'dark' : 'light');
            this.darkIcon.style.display = isDarkMode ? 'none' : 'block';
            this.lightIcon.style.display = isDarkMode ? 'block' : 'none';
        }

        bindEvents() {
            document.getElementById('prevMonth').addEventListener('click', () => this.prevMonth());
            document.getElementById('nextMonth').addEventListener('click', () => this.nextMonth());
            document.getElementById('reminderForm').addEventListener('submit', (e) => this.handleFormSubmit(e));
        }

        prevMonth() {
            this.currentMonth.setMonth(this.currentMonth.getMonth() - 1);
            this.updateCalendar();
        }

        nextMonth() {
            this.currentMonth.setMonth(this.currentMonth.getMonth() + 1);
            this.updateCalendar();
        }

        updateCalendar() {
            const monthYear = document.getElementById('monthYear');
            monthYear.textContent =
                `${this.monthNames[this.currentMonth.getMonth()]} ${this.currentMonth.getFullYear()}`;
            const calendar = document.getElementById('calendar');
            const existingDays = calendar.querySelectorAll('.calendar-day');
            existingDays.forEach(day => day.remove());
            const daysInMonth = new Date(this.currentMonth.getFullYear(), this.currentMonth.getMonth() + 1, 0)
                .getDate();
            const firstDayOfMonth = new Date(this.currentMonth.getFullYear(), this.currentMonth.getMonth(), 1)
                .getDay();
            for (let i = 0; i < firstDayOfMonth; i++) {
                const emptyDay = document.createElement('div');
                emptyDay.className = 'calendar-day empty';
                calendar.appendChild(emptyDay);
            }
            for (let day = 1; day <= daysInMonth; day++) {
                const dayElement = document.createElement('div');
                dayElement.className = 'calendar-day';
                dayElement.textContent = day;
                const date = new Date(this.currentMonth.getFullYear(), this.currentMonth.getMonth(), day);
                if (this.reminders.some(r => this.isSameDate(new Date(r.date), date))) {
                    dayElement.appendChild(document.createElement('div')).className = 'reminder-dot';
                }
                if (this.isSameDate(date, this.selectedDate)) {
                    dayElement.classList.add('selected');
                }
                dayElement.addEventListener('click', () => this.selectDate(date));
                calendar.appendChild(dayElement);
            }
        }

        selectDate(date) {
            this.selectedDate = new Date(date);
            this.updateCalendar();
            this.updateSelectedDate();
            this.updateReminderList();
        }

        updateSelectedDate() {
            document.getElementById('date').value = this.formatDateForInput(this.selectedDate);
        }

        formatDateForInput(date) {
            return date.toISOString().split('T')[0];
        }

        isSameDate(d1, d2) {
            return d1.getFullYear() === d2.getFullYear() && d1.getMonth() === d2.getMonth() && d1.getDate() === d2
                .getDate();
        }

        async handleFormSubmit(e) {
            e.preventDefault();
            const title = document.getElementById('title').value.trim();
            const recipient = document.getElementById('recipient').value.trim();
            const time = document.getElementById('time').value;

            if (!title || !recipient) {
                alert('Judul dan JID Penerima tidak boleh kosong!');
                return;
            }

            const [hours, minutes] = time.split(':');
            const reminderDate = new Date(this.selectedDate);
            reminderDate.setHours(hours, minutes, 0, 0);

            const postData = new FormData();
            postData.append('title', title);
            postData.append('description', document.getElementById('description').value.trim());
            postData.append('date', reminderDate.toISOString());
            postData.append('recipient', recipient);

            try {
                // PENTING: JavaScript memanggil file 'api.php' untuk memproses data
                const response = await fetch('api.php?action=add_reminder', {
                    method: 'POST',
                    body: postData
                });
                const result = await response.json();
                if (result.success) {
                    alert(result.message);
                    await this.loadReminders();
                    document.getElementById('reminderForm').reset();
                    document.getElementById('time').value = '12:00';
                } else {
                    alert('Gagal: ' + result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghubungi server.');
            }
        }

        updateReminderList() {
            const reminderList = document.getElementById('reminderList');
            const filtered = this.reminders
                .filter(r => this.isSameDate(new Date(r.date), this.selectedDate))
                .sort((a, b) => new Date(a.date) - new Date(b.date));

            if (filtered.length === 0) {
                reminderList.innerHTML = '<div class="no-reminders">Tidak ada pengingat untuk tanggal ini</div>';
                return;
            }

            reminderList.innerHTML = filtered.map(reminder => `
                    <div class="reminder-item" id="reminder-${reminder.id}">
                        <div class="reminder-header">
                            <h3 class="reminder-title">${this.escapeHtml(reminder.title)}</h3>
                            <button class="btn btn-danger" onclick="app.deleteReminder('${reminder.id}')" title="Hapus Pengingat">
                                <svg class="icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                        <div class="reminder-time">
                            <svg class="icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            ${this.formatTime(reminder.date)}
                        </div>
                        ${reminder.description ? `<p class="reminder-description">${this.escapeHtml(reminder.description)}</p>` : ''}
                    </div>
                `).join('');
        }

        async deleteReminder(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus pengingat ini?')) return;

            try {
                const postData = new FormData();
                postData.append('id', id);
                // PENTING: JavaScript memanggil file 'api.php'
                const response = await fetch('api.php?action=delete_reminder', {
                    method: 'POST',
                    body: postData
                });
                const result = await response.json();
                if (result.success) {
                    await this.loadReminders();
                } else {
                    alert('Gagal menghapus: ' + result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghubungi server.');
            }
        }

        async loadReminders() {
            try {
                // PENTING: JavaScript memanggil file 'api.php'
                const response = await fetch('api.php?action=get_reminders');
                this.reminders = await response.json();
                this.updateCalendar();
                this.updateReminderList();
            } catch (error) {
                console.error('Gagal memuat pengingat:', error);
                this.reminders = [];
            }
        }

        formatTime(dateStr) {
            return new Date(dateStr).toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            });
        }

        escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    }

    // Inisialisasi aplikasi
    const app = new CalendarApp();
    </script>
</body>

</html>