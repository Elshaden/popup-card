<template>


    <div v-if="showModal">
        <div class="modal-overlay ">
            <div class="modal-content text-gray-700 " :style="{ width: cardWidth }">
                <div class="modal-title">{{ modalContent.title }}</div>
                <div class="modal-body py-5 px-2 mb-2 mt-2" v-html="modalContent.body"></div>

                <div class="flex justify-between mt-5  bg-gray-100">
                    <div class="px-4 py-1">
                        <button @click="hideModal">Close</button>
                    </div>
                    <div class="px-4 py-1">
                        <button @click="doNotShowAgain">Do Not Show Again</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import axios from 'axios';

export default {

    props: ['card'],


    data() {
        return {
            showModal: false,
            popup_card_id: null,
            width: this.card.width,
            modalContent: {
                title: "",
                body: "",
            },
        };
    },
    computed: {

        cardWidth() {
            // Map the Nova width values to CSS width values
            const widthMap = {
                '1/4': '25%',
                '1/3': '33.33%',
                '1/2': '50%',
                '2/3': '66.66%',
                '3/4': '75%',
                full: '100%',
            };
            return widthMap[this.card.width] || '50%'; // Default to '1/3' if invalid
        },
    },

    created() {
        console.log("Width at creation:", this.card.width);

        this.checkIfModalShouldShow(); // Call on component initialization
    },

    methods: {
        checkIfModalShouldShow() {
            //  console.log("Checking if modal should show");

            // Make API request
            axios.get("/api/modal-content")
                .then(({data}) => {
                    console.log("API Response:", data);

                    // Use proper conditional logic for modal visibility
                    if (data.show_modal) {
                        this.showModal = true;
                        this.popup_card_id = data.popup_card_id ?? null;

                        this.modalContent = {
                            title: data.title || "Default Title",
                            body: data.body || "Default Body",
                        };
                        // console.log("Modal should be displayed with content:", this.modalContent);
                    } else {
                        //  console.log("Modal will not be shown (show_modal is false)");
                    }
                })
                .catch((error) => {
                    console.error("Error fetching modal content:", error.response || error.message);
                });
        },
        hideModal() {
            console.log("Hiding modal");
            this.showModal = false;
        },
        doNotShowAgain() {
            // console.log("Marking modal as seen");
            //   console.log("popup_card_id:", this.popup_card_id);

            if (this.popup_card_id) {

                // Mark modal as seen with an API request
                axios.post("/api/mark-modal-seen", {popup_card_id: this.popup_card_id})
                    .then(() => {
                        this.showModal = false;
                        //  console.log("Modal marked as seen and hidden.");
                    })
                    .catch((error) => {
                        console.error("Error marking modal seen:", error.response || error.message);
                    });
            } else {
                console.error("popup_card_id is not available.");
            }

        },
    },
};
</script>

<style>
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.modal-content {
    padding: 20px;
    background: white;
    border-radius: 10px;

}

.modal-title {
    font-size: 1.5rem;
    font-weight: bold;
    text-align: center;
}

.modal-body {
    font-size: 1rem;
}

</style>
