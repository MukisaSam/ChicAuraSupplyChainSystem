@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('supplier.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('supplier.supply-requests.index') }}">
                            <i class="fas fa-clipboard-list"></i> Supply Requests
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('supplier.supplied-items.index') }}">
                            <i class="fas fa-box"></i> Supplied Items
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('supplier.analytics') }}">
                            <i class="fas fa-chart-line"></i> Analytics
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('supplier.chat') }}">
                            <i class="fas fa-comments"></i> Chat
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('supplier.reports') }}">
                            <i class="fas fa-file-alt"></i> Reports
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Chat with Manufacturer</h1>
            </div>

            <div class="row">
                <!-- Chat Contacts -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Contacts</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                <a href="#" class="list-group-item list-group-item-action active" data-contact="manufacturer">
                                    <div class="d-flex align-items-center">
                                        <img src="https://via.placeholder.com/40" class="rounded-circle me-3" alt="Manufacturer">
                                        <div>
                                            <h6 class="mb-0">Manufacturer Team</h6>
                                            <small class="text-muted">Online</small>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action" data-contact="procurement">
                                    <div class="d-flex align-items-center">
                                        <img src="https://via.placeholder.com/40" class="rounded-circle me-3" alt="Procurement">
                                        <div>
                                            <h6 class="mb-0">Procurement Team</h6>
                                            <small class="text-muted">Last seen 2 hours ago</small>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action" data-contact="quality">
                                    <div class="d-flex align-items-center">
                                        <img src="https://via.placeholder.com/40" class="rounded-circle me-3" alt="Quality">
                                        <div>
                                            <h6 class="mb-0">Quality Control</h6>
                                            <small class="text-muted">Last seen 1 day ago</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chat Messages -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <img src="https://via.placeholder.com/32" class="rounded-circle me-2" alt="Manufacturer">
                                <h5 class="mb-0">Manufacturer Team</h5>
                                <span class="badge bg-success ms-2">Online</span>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <!-- Chat Messages Area -->
                            <div id="chat-messages" class="p-3" style="height: 400px; overflow-y: auto;">
                                <!-- Sample messages -->
                                <div class="d-flex mb-3">
                                    <img src="https://via.placeholder.com/32" class="rounded-circle me-2" alt="Manufacturer">
                                    <div class="bg-light p-3 rounded">
                                        <small class="text-muted">Manufacturer Team • 10:30 AM</small>
                                        <p class="mb-0">Hello! We have a new supply request for cotton fabric. Can you check your availability?</p>
                                    </div>
                                </div>
                                
                                <div class="d-flex mb-3 justify-content-end">
                                    <div class="bg-primary text-white p-3 rounded">
                                        <small class="text-white-50">You • 10:32 AM</small>
                                        <p class="mb-0">Hi! Yes, I can check. What quantity do you need?</p>
                                    </div>
                                    <img src="https://via.placeholder.com/32" class="rounded-circle ms-2" alt="You">
                                </div>
                                
                                <div class="d-flex mb-3">
                                    <img src="https://via.placeholder.com/32" class="rounded-circle me-2" alt="Manufacturer">
                                    <div class="bg-light p-3 rounded">
                                        <small class="text-muted">Manufacturer Team • 10:33 AM</small>
                                        <p class="mb-0">We need 5000 meters of premium cotton fabric. What's your best price?</p>
                                    </div>
                                </div>
                                
                                <div class="d-flex mb-3 justify-content-end">
                                    <div class="bg-primary text-white p-3 rounded">
                                        <small class="text-white-50">You • 10:35 AM</small>
                                        <p class="mb-0">For that quantity, I can offer $2.50 per meter. Delivery within 2 weeks.</p>
                                    </div>
                                    <img src="https://via.placeholder.com/32" class="rounded-circle ms-2" alt="You">
                                </div>
                            </div>
                            
                            <!-- Message Input -->
                            <div class="border-top p-3">
                                <form id="chat-form">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="message-input" placeholder="Type your message...">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatForm = document.getElementById('chat-form');
    const messageInput = document.getElementById('message-input');
    const chatMessages = document.getElementById('chat-messages');
    
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const message = messageInput.value.trim();
        if (!message) return;
        
        // Add message to chat
        const messageElement = document.createElement('div');
        messageElement.className = 'd-flex mb-3 justify-content-end';
        messageElement.innerHTML = `
            <div class="bg-primary text-white p-3 rounded">
                <small class="text-white-50">You • ${new Date().toLocaleTimeString()}</small>
                <p class="mb-0">${message}</p>
            </div>
            <img src="https://via.placeholder.com/32" class="rounded-circle ms-2" alt="You">
        `;
        
        chatMessages.appendChild(messageElement);
        chatMessages.scrollTop = chatMessages.scrollHeight;
        
        // Clear input
        messageInput.value = '';
        
        // Simulate response (in real app, this would be handled by WebSocket)
        setTimeout(() => {
            const responseElement = document.createElement('div');
            responseElement.className = 'd-flex mb-3';
            responseElement.innerHTML = `
                <img src="https://via.placeholder.com/32" class="rounded-circle me-2" alt="Manufacturer">
                <div class="bg-light p-3 rounded">
                    <small class="text-muted">Manufacturer Team • ${new Date().toLocaleTimeString()}</small>
                    <p class="mb-0">Thank you for the quick response. We'll review and get back to you shortly.</p>
                </div>
            `;
            
            chatMessages.appendChild(responseElement);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }, 1000);
    });
});
</script>
@endsection 