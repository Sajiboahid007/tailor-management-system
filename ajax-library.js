const getAjax = (url) => {
  return new Promise((result, failed) => {
    $.ajax({
      url: url,
      method: "GET",
      success: function (response) {
        result(response);
      },
      error: function (error) {
        failed(error);
      },
    });
  });
};

const saveAjax = (url, jsonData) => {
  return new Promise((resolve, reject) => {
    $.ajax({
      url: url,
      method: "POST",
      data: JSON.stringify(jsonData),
      contentType: "application/json",
      success: function (response) {
        resolve(response);
      },
      error: function (error) {
        reject(error);
      },
    });
  });
};

const deleteAjax = async (url) => {
  return new Promise((result, failed) => {
    $.ajax({
      url: url,
      method: "delete",
      success: function (res) {
        result(res);
      },
      error: function (error) {
        failed(error);
      },
    });
  });
};

const updateAjax = async (url, jsonData) => {
  return new Promise((result, failed) => {
    $.ajax({
      url: url,
      method: "PUT",
      data: JSON.stringify(jsonData),
      contentType: "application/json",
      success: function (res) {
        result(res);
      },
      error: function (error) {
        failed(error);
      },
    });
  });
};
