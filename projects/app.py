from flask import Flask, render_template, request, redirect, session, url_for
import sqlite3

app = Flask(__name__)
app.secret_key = 'your_secret_key'

# Dummy credentials
USERNAME = 'jefrin'
PASSWORD = 'jefrin123'

# Connect to DB and fetch approved requests
def get_approved_outpasses():
    conn = sqlite3.connect('studentdb.sql')
    cursor = conn.cursor()
    cursor.execute("""
        SELECT * FROM outpass_requests 
        WHERE rt_approved = 1 AND faculty_approved = 1 
              AND hod_approved = 1 AND principal_approved = 1
    """)
    results = cursor.fetchall()
    conn.close()
    return results

@app.route('/', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        if request.form['username'] == USERNAME and request.form['password'] == PASSWORD:
            session['user'] = USERNAME
            return redirect(url_for('dashboard'))
        else:
            return render_template('login.html', error="Invalid credentials")
    return render_template('login.html')

@app.route('/dashboard')
def dashboard():
    if 'user' not in session:
        return redirect('/')
    outpasses = get_approved_outpasses()
    return render_template('dashboard.html', outpasses=outpasses)

@app.route('/print/<int:outpass_id>')
def print_pass(outpass_id):
    if 'user' not in session:
        return redirect('/')
    conn = sqlite3.connect('hms.db')
    cursor = conn.cursor()
    cursor.execute("SELECT * FROM outpass_requests WHERE id = ?", (outpass_id,))
    data = cursor.fetchone()
    conn.close()
    return render_template('print_pass.html', data=data)

@app.route('/logout')
def logout():
    session.pop('user', None)
    return redirect('/')
