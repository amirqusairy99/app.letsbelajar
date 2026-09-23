import re
import os

def rewrite_dashboard():
    path = 'resources/views/dashboard.blade.php'
    with open(path, 'r') as f:
        content = f.read()

    # Layout & Grid
    content = re.sub(r'<div class="row g-3 mb-4">', '<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">', content)
    content = re.sub(r'<div class="row g-3">', '<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">', content)
    content = re.sub(r'<div class="col-12 col-sm-6 col-lg-3">', '<div>', content)
    content = re.sub(r'<div class="col-12 col-lg-8">', '<div class="lg:col-span-2 space-y-4">', content)
    content = re.sub(r'<div class="col-12 col-lg-4">', '<div class="space-y-4">', content)

    # Cards & Shadows -> Flat Shadcn style
    content = re.sub(r'<div class="card border-0 shadow-sm h-100 stat-card[^"]*">', '<div class="bg-white dark:bg-[#0c0a09] border border-gray-200 dark:border-gray-800 rounded-lg p-6 flex items-center gap-4">', content)
    content = re.sub(r'<div class="card border-0 shadow-sm[^"]*">', '<div class="bg-white dark:bg-[#0c0a09] border border-gray-200 dark:border-gray-800 rounded-lg">', content)
    content = re.sub(r'<div class="card-body d-flex align-items-center gap-3">', '', content) # Need to clean up stat cards HTML manually if this matches, but let's just do a simpler replace.
    
    with open(path, 'w') as f:
        f.write(content)

def rewrite_app():
    path = 'resources/views/layouts/app.blade.php'
    with open(path, 'r') as f:
        content = f.read()
    
    content = content.replace('<div class="d-flex min-vh-100">', '<div class="flex min-h-screen">')
    content = content.replace('<div class="sidebar-overlay d-lg-none"', '<div class="fixed inset-0 bg-black/50 z-40 lg:hidden hidden"')
    content = content.replace('<div class="flex-grow-1 d-flex flex-column" style="min-width: 0;">', '<div class="flex-1 flex flex-col min-w-0">')
    content = content.replace('<main class="flex-grow-1 p-3 p-lg-4 main-content">', '<main class="flex-1 p-4 lg:p-8">')
    
    with open(path, 'w') as f:
        f.write(content)

rewrite_app()
