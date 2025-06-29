<?php
// index.php
ob_start();
session_start();
// File ini hanya untuk tampilan, jadi tidak perlu ada koneksi database di sini.
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar Pengingat</title>
    <style>
    /* Semua kode CSS Anda tetap di sini, tidak ada perubahan */
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

    /* CSS untuk Modal Alarm */
    #alarm-modal {
        display: none;
        /* Sembunyikan secara default */
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
        /* Latar belakang semi-transparan */
        justify-content: center;
        align-items: center;
    }

    #alarm-modal-content {
        background-color: var(--card-bg);
        color: var(--text-color);
        margin: auto;
        padding: 2rem;
        border: 1px solid var(--border-color);
        width: 90%;
        max-width: 400px;
        border-radius: 0.5rem;
        text-align: center;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }

    #alarm-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    #alarm-text {
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
    }

    #stop-alarm-btn {
        background-color: var(--danger-color);
        color: white;
    }

    #stop-alarm-btn:hover {
        background-color: var(--danger-hover-color);
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

    .form-input[type="file"] {
        padding: 0.35rem 0.75rem;
    }

    .form-input[type="file"]::file-selector-button {
        margin-right: 0.5rem;
        border: none;
        background: var(--primary-color);
        padding: 0.3rem 0.6rem;
        border-radius: 0.25rem;
        color: white;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .form-input[type="file"]::file-selector-button:hover {
        background: var(--primary-hover-color);
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
    <!-- Tambahkan atribut 'loop' agar suara berulang -->
    <audio id="alarm-sound" src="ayam.mp3" preload="auto" loop></audio>

    <!-- Modal untuk menampilkan notifikasi alarm -->
    <div id="alarm-modal">
        <div id="alarm-modal-content">
            <h2 id="alarm-title">Waktunya Pengingat!</h2>
            <p id="alarm-text"></p>
            <button id="stop-alarm-btn" class="btn">Stop Alarm</button>
        </div>
    </div>


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

                        <div class="form-group">
                            <label class="form-label" for="alarm-file">Ganti Suara Alarm (Opsional)</label>
                            <input type="file" id="alarm-file" class="form-input" accept="audio/*">
                        </div>


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
    // --- BLOK JAVASCRIPT YANG BENAR UNTUK STRUKTUR 3 FILE ---
    class CalendarApp {
        constructor() {
            this.currentMonth = new Date();
            this.selectedDate = new Date();
            this.reminders = [];
            this.playedAlarms = new Set();
            this.monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                'September', 'Oktober', 'November', 'Desember'
            ];
            this.init();
        }

        async init() {
            this.alarmSound = document.getElementById('alarm-sound');
            this.alarmModal = document.getElementById('alarm-modal');
            this.alarmText = document.getElementById('alarm-text');
            this.stopAlarmBtn = document.getElementById('stop-alarm-btn');

            this.initTheme();
            this.bindEvents();
            await this.loadReminders();
            this.startAlarmChecker();
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
            document.getElementById('alarm-file').addEventListener('change', (e) => this.handleAlarmFileChange(e));
            // Tambahkan event listener untuk tombol stop
            this.stopAlarmBtn.addEventListener('click', () => this.stopAlarm());
        }

        handleAlarmFileChange(e) {
            const file = e.target.files[0];
            if (file) {
                const fileURL = URL.createObjectURL(file);
                this.alarmSound.src = fileURL;
                alert(`Suara alarm diganti menjadi: ${file.name}`);
            }
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

                const dayString = this.formatDateForInput(date);
                if (this.reminders.some(r => r.date.startsWith(dayString))) {
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
            const year = date.getFullYear();
            const month = (date.getMonth() + 1).toString().padStart(2, '0');
            const day = date.getDate().toString().padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        isSameDate(d1, d2) {
            return d1.getFullYear() === d2.getFullYear() && d1.getMonth() === d2.getMonth() && d1.getDate() === d2
                .getDate();
        }

        startAlarmChecker() {
            setInterval(() => {
                const now = new Date();
                this.reminders.forEach(reminder => {
                    const reminderTime = new Date(reminder.date.replace(' ', 'T'));
                    if (reminderTime.getFullYear() === now.getFullYear() &&
                        reminderTime.getMonth() === now.getMonth() &&
                        reminderTime.getDate() === now.getDate() &&
                        reminderTime.getHours() === now.getHours() &&
                        reminderTime.getMinutes() === now.getMinutes() &&
                        !this.playedAlarms.has(reminder.id)) {

                        this.playAlarm(reminder);
                    }
                });
            }, 10000); // Periksa setiap 10 detik
        }

        playAlarm(reminder) {
            this.alarmText.textContent = reminder.title;
            this.alarmModal.style.display = 'flex'; // Tampilkan modal
            this.alarmSound.play().catch(e => console.error("Gagal memutar suara:", e));
            this.playedAlarms.add(reminder.id);
        }

        stopAlarm() {
            this.alarmSound.pause(); // Hentikan suara
            this.alarmSound.currentTime = 0; // Reset waktu audio ke awal
            this.alarmModal.style.display = 'none'; // Sembunyikan modal
        }

        async handleRequest(url, options) {
            try {
                const response = await fetch(url, options);
                const responseText = await response.text();

                if (!response.ok) {
                    console.error("Server Error Response:", responseText);
                    alert(`Error: ${response.status} ${response.statusText}. Periksa konsol untuk detail.`);
                    return null;
                }

                try {
                    return JSON.parse(responseText);
                } catch (e) {
                    console.error("Gagal mem-parsing JSON. Respons mentah dari server:", responseText);
                    alert("Terjadi kesalahan pada server. Respons tidak valid.");
                    return null;
                }
            } catch (error) {
                console.error('Kesalahan Jaringan:', error);
                alert('Terjadi kesalahan saat menghubungi server. Periksa koneksi Anda.');
                return null;
            }
        }

        async handleFormSubmit(e) {
            e.preventDefault();
            const title = document.getElementById('title').value.trim();
            const dateValue = document.getElementById('date').value;
            const timeValue = document.getElementById('time').value;

            if (!title) {
                alert('Judul tidak boleh kosong!');
                return;
            }

            const dateTimeString = `${dateValue} ${timeValue}:00`;

            const postData = new FormData();
            postData.append('title', title);
            postData.append('description', document.getElementById('description').value.trim());
            postData.append('date', dateTimeString);

            const result = await this.handleRequest('api.php?action=add_reminder', {
                method: 'POST',
                body: postData
            });

            if (result) {
                if (result.success) {
                    alert(result.message);
                    await this.loadReminders();
                    document.getElementById('reminderForm').reset();
                    document.getElementById('time').value = '12:00';
                } else {
                    alert('Gagal dari server: ' + result.message);
                }
            }
        }

        updateReminderList() {
            const reminderList = document.getElementById('reminderList');
            const selectedDateString = this.formatDateForInput(this.selectedDate);

            const filtered = this.reminders
                .filter(r => r.date.startsWith(selectedDateString))
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
            // Hapus juga dari daftar alarm yang sudah berbunyi jika ada
            if (this.playedAlarms.has(id)) {
                this.playedAlarms.delete(id);
            }

            if (!confirm('Apakah Anda yakin ingin menghapus pengingat ini?')) return;

            const postData = new FormData();
            postData.append('id', id);

            const result = await this.handleRequest('api.php?action=delete_reminder', {
                method: 'POST',
                body: postData
            });

            if (result) {
                if (result.success) {
                    await this.loadReminders();
                } else {
                    alert('Gagal menghapus: ' + result.message);
                }
            }
        }

        async loadReminders() {
            const remindersData = await this.handleRequest('api.php?action=get_reminders');
            if (remindersData) {
                this.reminders = remindersData.error ? [] : remindersData;
                this.playedAlarms.clear();
            } else {
                this.reminders = [];
            }
            this.updateCalendar();
            this.updateSelectedDate();
            this.updateReminderList();
        }

        formatTime(dateStr) {
            const parsableDateStr = dateStr.replace(' ', 'T');
            return new Date(parsableDateStr).toLocaleTimeString('id-ID', {
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