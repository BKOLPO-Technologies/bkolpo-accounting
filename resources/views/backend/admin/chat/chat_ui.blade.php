@extends('layouts.admin', [$pageTitle => 'Conversations'])
@section('admin')
<style>
    /* Hover effect for <li> */
    #conversationList li:hover {
        background-color: #f0f0f0;
        /* Light gray background color */
        cursor: pointer;
    }

    /* Active message background color (primary color) */
    #conversationList li.active {
        background-color: #007bff;
        /* Bootstrap primary color */
        color: white;
        /* White text color to contrast with the primary background */
    }

    /* Active time text color */
    #conversationList li.active .time {
        color: white !important;
        /* White color for time when active, using !important */
    }

    /* Active dropdown icon color */
    #conversationList li.active .dropdown-icon {
        color: white !important;
        /* White color for the dropdown icon when active, using !important */
    }

    /* Ensure cursor pointer for the list items */
    .cursor-pointer {
        cursor: pointer;
    }

    /* Style for the conversation list */
    #conversationList {
        max-height: 300px;
        /* Adjust the height as needed */
        overflow-y: auto;
        /* Enables vertical scrolling */
        padding-right: 10px;
        /* Adds space to prevent scrollbar overlap */
    }

    /* Optional: Custom scrollbar style */
    #conversationList::-webkit-scrollbar {
        width: 8px;
        /* Adjust the width of the scrollbar */
    }

    #conversationList::-webkit-scrollbar-thumb {
        background-color: #888;
        /* Set scrollbar thumb color */
        border-radius: 4px;
        /* Rounded corners for scrollbar */
    }

    #conversationList::-webkit-scrollbar-thumb:hover {
        background-color: #555;
        /* Darker color when hovering over scrollbar */
    }
    /* Ensure tabs are in a single row */
    #settingsTab {
        display: flex;
        flex-wrap: nowrap;  /* Prevent wrapping */
    }

    #settingsTab .nav-item {
        margin-right: 0px;  /* Add some spacing between tabs */
    }

    /* Optional: Change the width of the tabs if needed */
    #settingsTab .nav-link {
        white-space: nowrap;  /* Prevent text wrapping within the tab link */
    }


</style>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $pageTitle ?? 'N/A'}}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active"> Chat / {{ $pageTitle ?? 'N/A' }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Online Users Section -->
                <!-- Online Users Section -->
                <div class="col-md-4">
                    <div class="card card-outline card-primary shadow">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <!-- Conversations Title -->
                            <h3 class="card-title">Conversations</h3>

                            <!-- Right-side Icons (User Profile and Settings) -->
                            <div class="ml-auto">
                                <button class="btn btn-link" data-toggle="modal" data-target="#userProfileModal"
                                    title="User Profile">
                                    <i class="nav-icon group-icon color-green remove-tooltip" data-bs-toggle="tooltip"
                                        data-bs-placement="bottom" title="" data-bs-original-title="New Group"
                                        aria-label="New Group">
                                        <img src="{{ asset('backend/dist/img/group.png') }}" width="33" height="33"></i>
                                </button>
                                <button class="btn btn-link" data-toggle="modal" data-target="#settingsModal"
                                    title="Settings">
                                    <i class="nav-icon remove-tooltip" data-bs-toggle="tooltip"
                                        data-bs-placement="bottom" title="" data-bs-original-title="New Conversation"
                                        aria-label="New Conversation">
                                        <img src="{{ asset('backend/dist/img/bubble-chat.png') }}" width="30"
                                            height="30">
                                    </i>
                                </button>

                                <!-- Modal for User Profile -->
                                <div class="modal fade" id="userProfileModal" tabindex="-1" role="dialog"
                                    aria-labelledby="userProfileModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="userProfileModalLabel">New Group</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form>
                                                    <!-- Group Name -->
                                                    <div class="form-group col-lg-12">
                                                        <label for="groupName">Group Name:</label>
                                                        <input type="text" class="form-control" id="groupName"
                                                            placeholder="Enter group name">
                                                    </div>

                                                    <!-- Group Icon -->
                                                    <div class="form-group col-lg-12">
                                                        <label for="groupIcon">Group Icon:</label>
                                                        <div class="d-flex align-items-center">
                                                            <!-- File input for the new Group Icon -->
                                                            <input type="file" class="form-control-file form-control"
                                                                id="groupIcon" onchange="updateIcon(event)">
                                                            <!-- Default Icon on the right -->
                                                            <div class="mr-0">
                                                                <img id="defaultGroupIcon"
                                                                    src="{{ asset('backend/dist/img/group-img.png') }}"
                                                                    width="100" height="100" alt="Default Icon">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Group Type (Open/Close) -->
                                                    <div class="form-group col-lg-12">
                                                        <label>Group Type:</label><br>
                                                        <div class="form-check form-check-inline icheck-success">
                                                            <input class="form-check-input" type="radio"
                                                                name="groupType" id="groupOpen" value="open">
                                                            <label class="form-check-label" for="groupOpen">Open</label>
                                                        </div>
                                                        <div class="form-check form-check-inline icheck-danger">
                                                            <input class="form-check-input" type="radio"
                                                                name="groupType" id="groupClose" value="close">
                                                            <label class="form-check-label"
                                                                for="groupClose">Close</label>
                                                        </div>
                                                    </div>

                                                    <!-- Group Privacy (Public/Private) -->
                                                    <div class="form-group col-lg-12">
                                                        <label>Group Privacy:</label><br>
                                                        <div class="form-check form-check-inline icheck-success">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="groupPublic" value="public">
                                                            <label class="form-check-label"
                                                                for="groupPublic">Public</label>
                                                        </div>
                                                        <div class="form-check form-check-inline icheck-primary">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="groupPrivate" value="private">
                                                            <label class="form-check-label"
                                                                for="groupPrivate">Private</label>
                                                        </div>

                                                </form>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal for Settings -->
                            <div class="modal fade" id="settingsModal" tabindex="-1" role="dialog"
                                aria-labelledby="settingsModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="settingsModalLabel">New Conversations / Groups</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Tab Navigation -->
                                            <ul class="nav nav-tabs" id="settingsTab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="contacts-tab" data-toggle="tab" href="#contacts" role="tab" aria-controls="contacts" aria-selected="true">My Contacts</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="newConversation-tab" data-toggle="tab" href="#newConversation" role="tab" aria-controls="newConversation" aria-selected="false">New Conversation</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="groups-tab" data-toggle="tab" href="#groups" role="tab" aria-controls="groups" aria-selected="false">Groups</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="blockedUsers-tab" data-toggle="tab" href="#blockedUsers" role="tab" aria-controls="blockedUsers" aria-selected="false">Blocked Users</a>
                                                </li>
                                            </ul>
                                            <div class="card shadow">
                                                <div class="card-body">
                                                    <!-- Search Bar Before the Tabs -->
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="searchContacts" placeholder="Search contacts...">
                                                    </div>

                                                    <!-- Tab Content -->
                                                    <div class="tab-content" id="settingsTabContent">
                                                        <div class="tab-pane fade show active" id="contacts" role="tabpanel" aria-labelledby="contacts-tab">
                                                            <!-- Conversations List -->
                                                            <ul class="list-unstyled" id="conversationList">
                                                                <li class="d-flex align-items-center mb-3 position-relative message-item">
                                                                    <!-- Image and Name aligned left -->
                                                                    <img src="{{ asset('backend/dist/img/avatar5.png') }}" alt="User Image"
                                                                        class="direct-chat-img mr-2">
                                                                    <span class="text-sm flex-grow-1">Nazrul Islam</span>

                                                                    <!-- Time aligned right -->
                                                                    <span class="text-xs text-muted ml-auto time">12:30 PM</span>

                                                                    <!-- Ellipsis Icon wrapped in a dropdown -->
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-link dropdown-toggle" type="button"
                                                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                                            aria-expanded="false">
                                                                            <i class="fa fa-ellipsis-v dropdown-icon"></i>
                                                                        </button>
                                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                            <a class="dropdown-item" href="#">Delete</a>
                                                                            <a class="dropdown-item" href="#">Archive Chat</a>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="d-flex align-items-center mb-3 position-relative message-item">
                                                                    <!-- Image and Name aligned left -->
                                                                    <img src="{{ asset('backend/dist/img/avatar5.png') }}" alt="User Image"
                                                                        class="direct-chat-img mr-2">
                                                                    <span class="text-sm flex-grow-1">Masud Rana</span>

                                                                    <!-- Time aligned right -->
                                                                    <span class="text-xs text-muted ml-auto time">12:45 PM</span>

                                                                    <!-- Ellipsis Icon wrapped in a dropdown -->
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-link dropdown-toggle" type="button"
                                                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                                            aria-expanded="false">
                                                                            <i class="fa fa-ellipsis-v dropdown-icon"></i>
                                                                        </button>
                                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                            <a class="dropdown-item" href="#">Delete</a>
                                                                            <a class="dropdown-item" href="#">Archive Chat</a>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="d-flex align-items-center mb-3 position-relative message-item">
                                                                    <!-- Image and Name aligned left -->
                                                                    <img src="{{ asset('backend/dist/img/avatar5.png') }}" alt="User Image"
                                                                        class="direct-chat-img mr-2">
                                                                    <span class="text-sm flex-grow-1">Rakibul Islam</span>

                                                                    <!-- Time aligned right -->
                                                                    <span class="text-xs text-muted ml-auto time">12:45 PM</span>

                                                                    <!-- Ellipsis Icon wrapped in a dropdown -->
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-link dropdown-toggle" type="button"
                                                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                                            aria-expanded="false">
                                                                            <i class="fa fa-ellipsis-v dropdown-icon"></i>
                                                                        </button>
                                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                            <a class="dropdown-item" href="#">Delete</a>
                                                                            <a class="dropdown-item" href="#">Archive Chat</a>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="d-flex align-items-center mb-3 position-relative message-item">
                                                                    <!-- Image and Name aligned left -->
                                                                    <img src="{{ asset('backend/dist/img/avatar5.png') }}" alt="User Image"
                                                                        class="direct-chat-img mr-2">
                                                                    <span class="text-sm flex-grow-1">Kamrul Islam</span>

                                                                    <!-- Time aligned right -->
                                                                    <span class="text-xs text-muted ml-auto time">12:45 PM</span>

                                                                    <!-- Ellipsis Icon wrapped in a dropdown -->
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-link dropdown-toggle" type="button"
                                                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                                            aria-expanded="false">
                                                                            <i class="fa fa-ellipsis-v dropdown-icon"></i>
                                                                        </button>
                                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                            <a class="dropdown-item" href="#">Delete</a>
                                                                            <a class="dropdown-item" href="#">Archive Chat</a>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="d-flex align-items-center mb-3 position-relative message-item">
                                                                    <!-- Image and Name aligned left -->
                                                                    <img src="{{ asset('backend/dist/img/avatar5.png') }}" alt="User Image"
                                                                        class="direct-chat-img mr-2">
                                                                    <span class="text-sm flex-grow-1">Zahidul Islam</span>

                                                                    <!-- Time aligned right -->
                                                                    <span class="text-xs text-muted ml-auto time">12:45 PM</span>

                                                                    <!-- Ellipsis Icon wrapped in a dropdown -->
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-link dropdown-toggle" type="button"
                                                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                                            aria-expanded="false">
                                                                            <i class="fa fa-ellipsis-v dropdown-icon"></i>
                                                                        </button>
                                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                            <a class="dropdown-item" href="#">Delete</a>
                                                                            <a class="dropdown-item" href="#">Archive Chat</a>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="d-flex align-items-center mb-3 position-relative message-item">
                                                                    <!-- Image and Name aligned left -->
                                                                    <img src="{{ asset('backend/dist/img/avatar5.png') }}" alt="User Image"
                                                                        class="direct-chat-img mr-2">
                                                                    <span class="text-sm flex-grow-1">Zishan Rana</span>

                                                                    <!-- Time aligned right -->
                                                                    <span class="text-xs text-muted ml-auto time">12:45 PM</span>

                                                                    <!-- Ellipsis Icon wrapped in a dropdown -->
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-link dropdown-toggle" type="button"
                                                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                                            aria-expanded="false">
                                                                            <i class="fa fa-ellipsis-v dropdown-icon"></i>
                                                                        </button>
                                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                            <a class="dropdown-item" href="#">Delete</a>
                                                                            <a class="dropdown-item" href="#">Archive Chat</a>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="tab-pane fade" id="newConversation" role="tabpanel" aria-labelledby="newConversation-tab">
                                                            <p>Start a new conversation here.</p>
                                                        </div>
                                                        <div class="tab-pane fade" id="groups" role="tabpanel" aria-labelledby="groups-tab">
                                                            <ul class="list-group mt-3">
                                                                <li class="list-group-item">Group 1</li>
                                                                <li class="list-group-item">Group 2</li>
                                                                <li class="list-group-item">Group 3</li>
                                                            </ul>
                                                        </div>
                                                        <div class="tab-pane fade" id="blockedUsers" role="tabpanel" aria-labelledby="blockedUsers-tab">
                                                            <ul class="list-group mt-3">
                                                                <li class="list-group-item">Blocked User 1</li>
                                                                <li class="list-group-item">Blocked User 2</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Search Input -->
                        <div class="mb-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-search"></i></span>
                                </div>
                                <input type="text" class="form-control" id="conversationSearch"
                                    placeholder="Search conversations...">
                            </div>
                        </div>

                        <!-- Tabs for Active and Archived Chats -->
                        <div class="mt-3">
                            <ul class="nav nav-pills">
                                <!-- Active Chat Tab -->
                                <li class="nav-item">
                                    <a class="nav-link active" id="activeChatTab" href="#">Active Chat</a>
                                </li>
                                <!-- Archived Chat Tab -->
                                <li class="nav-item">
                                    <a class="nav-link" id="archivedChatTab" href="#">Archived Chat</a>
                                </li>
                            </ul>

                            <div class="tab-content mt-2">
                                <!-- Active Chat Content -->
                                <div class="tab-pane fade show active" id="activeChatContent">
                                    <!-- Conversations List -->
                                    <ul class="list-unstyled" id="conversationList">
                                        <li class="d-flex align-items-center mb-3 position-relative message-item">
                                            <!-- Image and Name aligned left -->
                                            <img src="{{ asset('backend/dist/img/avatar5.png') }}" alt="User Image"
                                                class="direct-chat-img mr-2">
                                            <span class="text-sm flex-grow-1">Nazrul Islam</span>

                                            <!-- Time aligned right -->
                                            <span class="text-xs text-muted ml-auto time">12:30 PM</span>

                                            <!-- Ellipsis Icon wrapped in a dropdown -->
                                            <div class="dropdown">
                                                <button class="btn btn-link dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v dropdown-icon"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="#">Delete</a>
                                                    <a class="dropdown-item" href="#">Archive Chat</a>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="d-flex align-items-center mb-3 position-relative message-item">
                                            <!-- Image and Name aligned left -->
                                            <img src="{{ asset('backend/dist/img/avatar5.png') }}" alt="User Image"
                                                class="direct-chat-img mr-2">
                                            <span class="text-sm flex-grow-1">Masud Rana</span>

                                            <!-- Time aligned right -->
                                            <span class="text-xs text-muted ml-auto time">12:45 PM</span>

                                            <!-- Ellipsis Icon wrapped in a dropdown -->
                                            <div class="dropdown">
                                                <button class="btn btn-link dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v dropdown-icon"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="#">Delete</a>
                                                    <a class="dropdown-item" href="#">Archive Chat</a>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="d-flex align-items-center mb-3 position-relative message-item">
                                            <!-- Image and Name aligned left -->
                                            <img src="{{ asset('backend/dist/img/avatar5.png') }}" alt="User Image"
                                                class="direct-chat-img mr-2">
                                            <span class="text-sm flex-grow-1">Rakibul Islam</span>

                                            <!-- Time aligned right -->
                                            <span class="text-xs text-muted ml-auto time">12:45 PM</span>

                                            <!-- Ellipsis Icon wrapped in a dropdown -->
                                            <div class="dropdown">
                                                <button class="btn btn-link dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v dropdown-icon"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="#">Delete</a>
                                                    <a class="dropdown-item" href="#">Archive Chat</a>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="d-flex align-items-center mb-3 position-relative message-item">
                                            <!-- Image and Name aligned left -->
                                            <img src="{{ asset('backend/dist/img/avatar5.png') }}" alt="User Image"
                                                class="direct-chat-img mr-2">
                                            <span class="text-sm flex-grow-1">Kamrul Islam</span>

                                            <!-- Time aligned right -->
                                            <span class="text-xs text-muted ml-auto time">12:45 PM</span>

                                            <!-- Ellipsis Icon wrapped in a dropdown -->
                                            <div class="dropdown">
                                                <button class="btn btn-link dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v dropdown-icon"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="#">Delete</a>
                                                    <a class="dropdown-item" href="#">Archive Chat</a>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="d-flex align-items-center mb-3 position-relative message-item">
                                            <!-- Image and Name aligned left -->
                                            <img src="{{ asset('backend/dist/img/avatar5.png') }}" alt="User Image"
                                                class="direct-chat-img mr-2">
                                            <span class="text-sm flex-grow-1">Zahidul Islam</span>

                                            <!-- Time aligned right -->
                                            <span class="text-xs text-muted ml-auto time">12:45 PM</span>

                                            <!-- Ellipsis Icon wrapped in a dropdown -->
                                            <div class="dropdown">
                                                <button class="btn btn-link dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v dropdown-icon"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="#">Delete</a>
                                                    <a class="dropdown-item" href="#">Archive Chat</a>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="d-flex align-items-center mb-3 position-relative message-item">
                                            <!-- Image and Name aligned left -->
                                            <img src="{{ asset('backend/dist/img/avatar5.png') }}" alt="User Image"
                                                class="direct-chat-img mr-2">
                                            <span class="text-sm flex-grow-1">Zishan Rana</span>

                                            <!-- Time aligned right -->
                                            <span class="text-xs text-muted ml-auto time">12:45 PM</span>

                                            <!-- Ellipsis Icon wrapped in a dropdown -->
                                            <div class="dropdown">
                                                <button class="btn btn-link dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v dropdown-icon"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="#">Delete</a>
                                                    <a class="dropdown-item" href="#">Archive Chat</a>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <!-- Archived Chat Content -->
                                <div class="tab-pane fade" id="archivedChatContent">
                                    <!-- Archived chat content goes here -->
                                    <!-- Conversations List -->
                                    <ul class="list-unstyled" id="conversationList">
                                        <li class="d-flex align-items-center mb-3 position-relative message-item">
                                            <!-- Image and Name aligned left -->
                                            <img src="{{ asset('backend/dist/img/avatar5.png') }}" alt="User Image"
                                                class="direct-chat-img mr-2">
                                            <span class="text-sm flex-grow-1">Asraful Islam</span>

                                            <!-- Time aligned right -->
                                            <span class="text-xs text-muted ml-auto time">12:30 PM</span>

                                            <!-- Ellipsis Icon wrapped in a dropdown -->
                                            <div class="dropdown">
                                                <button class="btn btn-link dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v dropdown-icon"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="#">Delete</a>
                                                    <a class="dropdown-item" href="#">Archive Chat</a>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="d-flex align-items-center mb-3 position-relative message-item">
                                            <!-- Image and Name aligned left -->
                                            <img src="{{ asset('backend/dist/img/avatar5.png') }}" alt="User Image"
                                                class="direct-chat-img mr-2">
                                            <span class="text-sm flex-grow-1">Rifat Zahan Zim</span>

                                            <!-- Time aligned right -->
                                            <span class="text-xs text-muted ml-auto time">12:45 PM</span>

                                            <!-- Ellipsis Icon wrapped in a dropdown -->
                                            <div class="dropdown">
                                                <button class="btn btn-link dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v dropdown-icon"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="#">Delete</a>
                                                    <a class="dropdown-item" href="#">Archive Chat</a>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chat Section -->
            <div class="col-md-8">
                <div class="card card-primary card-outline shadow">
                    <div class="card-header">
                        <h3 class="card-title">Chat with a</h3>
                    </div>
                    <div class="card-body">
                        <div class="direct-chat direct-chat-primary">
                            <div class="direct-chat-messages" id="messages-box">
                                <!-- Chat Messages will be added dynamically here -->
                                <div class="direct-chat-msg">
                                    <div class="direct-chat-infos clearfix">
                                        <span class="direct-chat-name float-left">Nazrul Islam</span>
                                        <span class="direct-chat-timestamp float-right">12:30 PM</span>
                                    </div>
                                    <img class="direct-chat-img" src="{{ asset('backend/dist/img/avatar5.png') }}"
                                        alt="User Image">
                                    <div class="direct-chat-text">
                                        Hello! How can I assist you today?
                                    </div>
                                </div>
                                <div class="direct-chat-msg right">
                                    <div class="direct-chat-infos clearfix">
                                        <span class="direct-chat-name float-right">You</span>
                                        <span class="direct-chat-timestamp float-left">12:32 PM</span>
                                    </div>
                                    <img class="direct-chat-img" src="{{ asset('backend/dist/img/avatar5.png') }}"
                                        alt="User Image">
                                    <div class="direct-chat-text">
                                        Hi, I have a question about the product.
                                    </div>
                                </div>
                            </div>
                            <!-- /.direct-chat-messages -->

                            <!-- Message Input -->
                            <div class="input-group">
                                <input type="text" class="form-control" id="messageInput"
                                    placeholder="Type a message...">
                                <span class="input-group-append">
                                    <button type="button" class="btn btn-primary" id="sendMessageButton"><i
                                            class="fa fa-paper-plane"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
</section>
</div>

@endsection

@push('js')
<script>
    // Add event listeners to tab links
    document.getElementById('activeChatTab').addEventListener('click', function () {
        document.getElementById('activeChatContent').classList.add('show', 'active');
        document.getElementById('archivedChatContent').classList.remove('show', 'active');
        this.classList.add('active');
        document.getElementById('archivedChatTab').classList.remove('active');
    });

    document.getElementById('archivedChatTab').addEventListener('click', function () {
        document.getElementById('archivedChatContent').classList.add('show', 'active');
        document.getElementById('activeChatContent').classList.remove('show', 'active');
        this.classList.add('active');
        document.getElementById('activeChatTab').classList.remove('active');
    });

</script>

<!-- jQuery Script to toggle active class -->
<script>
    $(document).ready(function () {
        // When a list item is clicked
        $('#conversationList li').on('click', function () {
            // Remove 'active' class from all list items
            $('#conversationList li').removeClass('active');

            // Add 'active' class to the clicked list item
            $(this).addClass('active');
        });
    });

</script>
<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

</script>
<!-- JavaScript to Update Icon Preview -->
<script>
    function updateIcon(event) {
        // Get the selected file
        const file = event.target.files[0];
        if (file) {
            // Create a URL for the selected file and set it as the new image source
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('defaultGroupIcon').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }

</script>
@endpush
