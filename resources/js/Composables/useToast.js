import { useToast } from "vue-toastification";

export const toastSuccess = (message) => {
    const toast = useToast();
    if (message) {
        return toast.success(message, {
            timeout: 2000,
            closeOnClick: true,
            icon: true,
        });
    }
};

export const toastError = (message) => {
    const toast = useToast();
    return toast.error(message, {
        timeout: 2000,
        closeOnClick: true,
        icon: true,
    });
};
