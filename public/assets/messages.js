document.addEventListener('alpine:init', () => {
    Alpine.data('messagesComponent', () => ({
        conversations: [],
        filteredConversations: [],
        currentMessages: [],
        selectedConversation: null,
        newMessage: '',
        searchQuery: '',

        /* ===================== */
        async init() {
            await this.loadConversations()

            setInterval(() => {
                if (this.selectedConversation) {
                    this.loadMessages(this.selectedConversation.conversation_id)
                }
            }, 2000)
        },

        /* ===================== */
        async loadConversations() {
            const res = await fetch('/api/conversations')
            this.conversations = await res.json()
            this.filterConversations()
        },

        /* ===================== */
        filterConversations() {
            const q = this.searchQuery.toLowerCase()
            this.filteredConversations = this.conversations.filter(c =>
                c.other_user_name.toLowerCase().includes(q)
            )
        },

        /* ===================== */
        async selectConversation(conversation) {
            this.selectedConversation = conversation
            await this.loadMessages(conversation.conversation_id)
        },

        /* ===================== */
        async loadMessages(conversationId) {
            const res = await fetch(`/api/messages/${conversationId}`)
            this.currentMessages = await res.json()

            this.$nextTick(() => this.scrollToBottom())
        },

        /* ===================== */
        async sendMessage() {
            if (!this.newMessage.trim()) return

            await fetch('/api/messages/send', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    conversation_id: this.selectedConversation.conversation_id,
                    message: this.newMessage
                })
            })

            this.newMessage = ''
            await this.loadMessages(this.selectedConversation.conversation_id)
        },

        /* ===================== */
        scrollToBottom() {
            const el = document.getElementById('chat-messages')
            if (el) el.scrollTop = el.scrollHeight
        }
    }))
})
