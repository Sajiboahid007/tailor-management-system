const successMessage = (message = "Successfully Saved") => {
  Swal.fire({
    position: "center",
    icon: "success",
    title: message,
    showConfirmButton: false,
    timer: 1500,
  });
};

const errorMessage = (error) => {
  let message = "Something went wrong!";
  if (error?.responseJSON?.message) {
    message = error?.responseJSON?.message;
  } else if (error?.statusText) {
    message = error?.statusText;
  } else if (error?.message) {
    message = error?.message;
  }

  Swal.fire({
    position: "center",
    icon: "error",
    title: message,
    showConfirmButton: false,
    timer: 1500,
  });
};

const deleteConfirmation = () => {
  return new Promise((resolve, reject) => {
    Swal.fire({
      title: "Are you sure?",
      text: "You won't be able to revert this!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, delete it!",
    }).then((result) => {
      if (result.isConfirmed) {
        resolve(true);
      } else {
        reject(true);
      }
    });
  });
};
