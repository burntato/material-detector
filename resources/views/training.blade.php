@extends('layout')

@section('title', 'Training Page')

@section('content')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <h2>Initiate Training</h2>
                <p>Press the button below to start training all ready materials. Do not close this page until the process is
                    complete.</p>
                <form method="POST" action="{{ route('train.model') }}" onsubmit="showLoading()">
                    @csrf
                    <button id="startTrainingButton" type="submit" class="btn btn-primary">Start Training All Ready
                        Materials</button>
                </form>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-12">
                <h2>Resume Training</h2>
                <p>Press the button below to resume training from where you left off. Do not close this page until the
                    process is complete.</p>
                <form method="POST" action="{{ route('resume.training') }}" onsubmit="showLoading()">
                    @csrf
                    <button id="resumeTrainingButton" type="submit" class="btn btn-primary">Resume Training</button>
                </form>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-6">
                <h4>Materials Ready for Training</h4>
                <table class="table table-bordered" style="border-color: green;">
                    <tbody id="readyMaterialsTable">
                        @foreach ($materials->where('ready', true) as $material)
                            <tr>
                                <td>{{ $material->mat_name }}</td>
                                <td>{{ $material->img_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <h4>Materials Not Ready for Training</h4>
                <table class="table table-bordered" style="border-color: red;">
                    @foreach ($materials->where('ready', false) as $material)
                        <tr>
                            <td>{{ $material->mat_name }}</td>
                            <td>{{ $material->img_count }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loading"
        style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); color: white; text-align: center; padding-top: 20%;">
        <h2>Loading... Please wait.</h2>
    </div>

    <script>
        $(document).ready(function() {
            // Initially disable the buttons
            const startTrainingButton = $('#startTrainingButton');
            const resumeTrainingButton = $('#resumeTrainingButton');

            startTrainingButton.prop('disabled', true);
            resumeTrainingButton.prop('disabled', true);

            // Check if there are any ready materials
            const readyMaterialsTable = $('#readyMaterialsTable');
            if (readyMaterialsTable.children().length > 0) {
                startTrainingButton.prop('disabled', false);
                resumeTrainingButton.prop('disabled', false);
            }
        });

        function showLoading() {
            $('#loading').show();
        }
    </script>
@endsection
